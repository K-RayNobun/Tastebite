<?php

class Database {
    private $pdo;
    private $is_fallback = false;
    private $fallback_data = null;
    private $json_path = __DIR__ . '/../data/db.json';

    public function __construct() {
        // DB Settings from docker-compose/env
        $host = 'db';
        $db   = 'tastebite';
        $user = 'tastebite_user';
        $pass = 'tastebite_pass';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
            $this->ensureSchema();
        } catch (\PDOException $e) {
            log_error("Database Connection Failure: " . $e->getMessage(), 'CRITICAL');
            $this->is_fallback = true;
            $this->loadFallbackData();
        }
    }

    private function loadFallbackData() {
        if (file_exists($this->json_path)) {
            $json = file_get_contents($this->json_path);
            $this->fallback_data = json_decode($json, true);
            // Inject a global warning for admin
            if (isset($_SESSION['user_id'])) {
                // Potential notice here
            }
        }
    }

    public function isReadOnly() {
        return $this->is_fallback;
    }

    private function ensureSchema() {
        // Minimal check to see if categories table exists
        $stmt = $this->pdo->query("SHOW TABLES LIKE 'categories'");
        if ($stmt->rowCount() == 0) {
            // This is a new DB, suggest running migrate.php
            // We don't auto-run it here for production safety.
        }
    }

    public function getRecipeById($id) {
        if ($this->is_fallback) {
            foreach ($this->fallback_data['recipes'] as $r) {
                if ($r['id'] == $id) return $r;
            }
            return null;
        }
        
        $stmt = $this->pdo->prepare("SELECT r.*, c.name as category_name, u.name as author, u.avatar as author_avatar 
                                   FROM recipes r 
                                   LEFT JOIN categories c ON r.category_id = c.id 
                                   LEFT JOIN users u ON r.author_id = u.id 
                                   WHERE r.id = ?");
        $stmt->execute([$id]);
        $recipe = $stmt->fetch();
        
        if ($recipe) {
            // Fetch ingredients
            $ing_stmt = $this->pdo->prepare("SELECT * FROM recipe_ingredients WHERE recipe_id = ?");
            $ing_stmt->execute([$id]);
            $recipe['ingredients'] = $ing_stmt->fetchAll();
            
            // Fetch instructions
            $inst_stmt = $this->pdo->prepare("SELECT instruction FROM recipe_instructions WHERE recipe_id = ? ORDER BY step_number ASC");
            $inst_stmt->execute([$id]);
            $recipe['instructions'] = array_column($inst_stmt->fetchAll(), 'instruction');
        }
        
        return $recipe;
    }

    public function getRecipes($options = []) {
        if ($this->is_fallback) {
            $results = $this->fallback_data['recipes'];
            if (is_callable($options)) return array_filter($results, $options);
            if (isset($options['category'])) {
                $results = array_filter($results, function($r) use ($options) {
                    return strtolower($r['category']) === strtolower($options['category']);
                });
            }
            if (isset($options['limit'])) $results = array_slice($results, 0, $options['limit']);
            return $results;
        }

        $where = [];
        $params = [];
        
        $sql = "SELECT r.*, c.name as category, u.name as author 
                FROM recipes r 
                LEFT JOIN categories c ON r.category_id = c.id 
                LEFT JOIN users u ON r.author_id = u.id";

        if (is_callable($options)) {
            // Legacy Support (Poor performance)
            $stmt = $this->pdo->query($sql . " ORDER BY r.created_at DESC");
            return array_filter($stmt->fetchAll(), $options);
        }

        if (isset($options['category'])) {
            $where[] = "c.name = ?";
            $params[] = $options['category'];
        }

        if (isset($options['author_id'])) {
            $where[] = "r.author_id = ?";
            $params[] = (int)$options['author_id'];
        }

        if (isset($options['search'])) {
            $where[] = "(r.title LIKE ? OR c.name LIKE ?)";
            $params[] = "%".$options['search']."%";
            $params[] = "%".$options['search']."%";
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY r.created_at DESC";

        if (isset($options['limit'])) {
            $sql .= " LIMIT " . (int)$options['limit'];
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getLatestRecipes($limit = 6) {
        if ($this->is_fallback) {
            $latest = array_filter($this->fallback_data['recipes'], function($r) {
                return isset($r['is_latest']) && $r['is_latest'] === true;
            });
            return array_slice($latest, 0, $limit);
        }
        $stmt = $this->pdo->prepare("SELECT r.*, c.name as category FROM recipes r LEFT JOIN categories c ON r.category_id = c.id WHERE r.is_latest = 1 ORDER BY r.created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getSuperDeliciousRecipes($limit = 3) {
        if ($this->is_fallback) {
            $super = array_filter($this->fallback_data['recipes'], function($r) {
                return isset($r['is_super_delicious']) && $r['is_super_delicious'] === true;
            });
            return array_slice($super, 0, $limit);
        }
        $stmt = $this->pdo->prepare("SELECT r.*, c.name as category FROM recipes r LEFT JOIN categories c ON r.category_id = c.id WHERE r.is_super_delicious = 1 ORDER BY r.created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getCategories() {
        if ($this->is_fallback) return $this->fallback_data['categories'];
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function searchRecipes($query) {
        if ($this->is_fallback) {
            $query = strtolower($query);
            return array_filter($this->fallback_data['recipes'], function($r) use ($query) {
                return strpos(strtolower($r['title']), $query) !== false || 
                       strpos(strtolower($r['category']), $query) !== false;
            });
        }
        $stmt = $this->pdo->prepare("SELECT * FROM recipes WHERE title LIKE ? OR id IN (SELECT id FROM recipes WHERE category_id IN (SELECT id FROM categories WHERE name LIKE ?))");
        $q = "%$query%";
        $stmt->execute([$q, $q]);
        return $stmt->fetchAll();
    }

    public function getUser($email) {
        if ($this->is_fallback) {
            foreach ($this->fallback_data['users'] as $u) {
                if ($u['email'] === $email) return $u;
            }
            return null;
        }

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function getUserBySocial($provider, $socialId) {
        if ($this->isReadOnly()) return null;
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE auth_provider = ? AND social_id = ?");
        $stmt->execute([$provider, $socialId]);
        return $stmt->fetch();
    }

    public function createUser($userData) {
        if ($this->is_fallback) {
            log_error("Attempted to create user in Read-Only Mode", 'WARNING');
            return false;
        }

        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, avatar, auth_provider, social_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $userData['name'] ?? ($userData['first_name'] . ' ' . $userData['last_name']),
            $userData['email'],
            $userData['password'] ?? null,
            $userData['avatar'] ?? '',
            $userData['auth_provider'] ?? 'email',
            $userData['social_id'] ?? null
        ]);
        $userData['id'] = $this->pdo->lastInsertId();
        return $userData;
    }

    public function addRecipe($recipeData) {
        if ($this->is_fallback) {
            log_error("Attempted to add recipe in Read-Only Mode", 'WARNING');
            return false;
        }

        // First find or create category ID
        $cat_stmt = $this->pdo->prepare("SELECT id FROM categories WHERE name = ?");
        $cat_stmt->execute([$recipeData['category']]);
        $cat = $cat_stmt->fetch();
        $cat_id = $cat ? $cat['id'] : null;

        $stmt = $this->pdo->prepare("INSERT INTO recipes (title, category_id, author_id, image, rating, is_latest, is_super_delicious) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $recipeData['title'],
            $cat_id,
            $recipeData['author_id'] ?? 1,
            $recipeData['image'] ?? '',
            $recipeData['rating'] ?? 5,
            $recipeData['is_latest'] ?? true,
            $recipeData['is_super_delicious'] ?? false
        ]);
        
        $recipe_id = $this->pdo->lastInsertId();
        $recipeData['id'] = $recipe_id;

        // Add ingredients
        if (isset($recipeData['ingredients'])) {
            foreach ($recipeData['ingredients'] as $ing) {
                $i_stmt = $this->pdo->prepare("INSERT INTO recipe_ingredients (recipe_id, name, amount, unit) VALUES (?, ?, ?, ?)");
                $i_stmt->execute([$recipe_id, $ing['name'], $ing['amount'] ?? '', $ing['unit'] ?? '']);
            }
        }

        // Add instructions
        if (isset($recipeData['instructions'])) {
            $step = 1;
            foreach ($recipeData['instructions'] as $inst) {
                $in_stmt = $this->pdo->prepare("INSERT INTO recipe_instructions (recipe_id, step_number, instruction) VALUES (?, ?, ?)");
                $in_stmt->execute([$recipe_id, $step++, $inst]);
            }
        }

        return $recipeData;
    }

    public function toggleSaveRecipe($userId, $recipeId) {
        if ($this->isReadOnly()) return false;
        
        try {
            // Check if already saved
            $check = $this->pdo->prepare("SELECT id FROM user_saved_recipes WHERE user_id = ? AND recipe_id = ?");
            $check->execute([$userId, $recipeId]);
            
            if ($check->rowCount() > 0) {
                // Remove
                $stmt = $this->pdo->prepare("DELETE FROM user_saved_recipes WHERE user_id = ? AND recipe_id = ?");
                $stmt->execute([$userId, $recipeId]);
                return 'unsaved';
            } else {
                // Add
                $stmt = $this->pdo->prepare("INSERT INTO user_saved_recipes (user_id, recipe_id) VALUES (?, ?)");
                $stmt->execute([$userId, $recipeId]);
                return 'saved';
            }
        } catch (PDOException $e) {
            log_error("Error toggling save recipe: " . $e->getMessage());
            return false;
        }
    }

    public function isRecipeSaved($userId, $recipeId) {
        if (!$userId || $this->isReadOnly()) return false;
        $stmt = $this->pdo->prepare("SELECT id FROM user_saved_recipes WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$userId, $recipeId]);
        return $stmt->rowCount() > 0;
    }

    public function updateRecipe($id, $recipeData) {
        if ($this->isReadOnly()) return false;
        
        try {
            // Check ownership
            $check = $this->pdo->prepare("SELECT author_id FROM recipes WHERE id = ?");
            $check->execute([$id]);
            $owner = $check->fetch();
            if (!$owner || $owner['author_id'] != $recipeData['author_id']) return false;

            // Update main recipe
            $stmt = $this->pdo->prepare("UPDATE recipes SET title = ?, category_id = ?, image = ?, rating = ? WHERE id = ?");
            
            $cat_stmt = $this->pdo->prepare("SELECT id FROM categories WHERE name = ?");
            $cat_stmt->execute([$recipeData['category']]);
            $cat = $cat_stmt->fetch();
            $cat_id = $cat ? $cat['id'] : null;

            $stmt->execute([
                $recipeData['title'],
                $cat_id,
                $recipeData['image'],
                $recipeData['rating'],
                $id
            ]);

            // Clear and re-add ingredients/instructions
            $this->pdo->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = ?")->execute([$id]);
            foreach ($recipeData['ingredients'] as $ing) {
                $i_stmt = $this->pdo->prepare("INSERT INTO recipe_ingredients (recipe_id, name, amount, unit) VALUES (?, ?, ?, ?)");
                $i_stmt->execute([$id, $ing['name'], $ing['amount'] ?? '', $ing['unit'] ?? '']);
            }

            $this->pdo->prepare("DELETE FROM recipe_instructions WHERE recipe_id = ?")->execute([$id]);
            $step = 1;
            foreach ($recipeData['instructions'] as $inst) {
                $in_stmt = $this->pdo->prepare("INSERT INTO recipe_instructions (recipe_id, step_number, instruction) VALUES (?, ?, ?)");
                $in_stmt->execute([$id, $step++, $inst]);
            }

            return true;
        } catch (PDOException $e) {
            log_error("Error updating recipe: " . $e->getMessage());
            return false;
        }
    }

    public function deleteRecipe($id, $userId) {
        if ($this->isReadOnly()) return false;
        try {
            $stmt = $this->pdo->prepare("DELETE FROM recipes WHERE id = ? AND author_id = ?");
            return $stmt->execute([$id, $userId]);
        } catch (PDOException $e) {
            log_error("Error deleting recipe: " . $e->getMessage());
            return false;
        }
    }
}
