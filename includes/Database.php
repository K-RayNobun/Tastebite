<?php

class Database {
    private $db_path;
    private $data;

    public function __construct($path = __DIR__ . '/../data/db.json') {
        $this->db_path = $path;
        $this->loadData();
    }

    private function loadData() {
        if (!file_exists($this->db_path)) {
            $this->data = ["users" => [], "recipes" => [], "categories" => []];
            $this->saveData();
        } else {
            $json = file_get_contents($this->db_path);
            $this->data = json_decode($json, true);
        }
    }

    private function saveData() {
        file_put_contents($this->db_path, json_encode($this->data, JSON_PRETTY_PRINT), LOCK_EX);
    }

    public function getRecipeById($id) {
        foreach ($this->data['recipes'] as $recipe) {
            if ($recipe['id'] == $id) return $recipe;
        }
        return null;
    }

    public function getRecipes($filter = null) {
        if (!$filter) return $this->data['recipes'];
        
        return array_filter($this->data['recipes'], $filter);
    }

    public function getLatestRecipes($limit = 6) {
        $latest = array_filter($this->data['recipes'], function($r) {
            return isset($r['is_latest']) && $r['is_latest'] === true;
        });
        return array_slice($latest, 0, $limit);
    }

    public function getSuperDeliciousRecipes($limit = 3) {
        $super = array_filter($this->data['recipes'], function($r) {
            return isset($r['is_super_delicious']) && $r['is_super_delicious'] === true;
        });
        return array_slice($super, 0, $limit);
    }

    public function getCategories() {
        return $this->data['categories'];
    }

    public function searchRecipes($query) {
        $query = strtolower($query);
        return array_filter($this->data['recipes'], function($r) use ($query) {
            return strpos(strtolower($r['title']), $query) !== false || 
                   strpos(strtolower($r['category']), $query) !== false;
        });
    }

    public function getUser($email) {
        foreach ($this->data['users'] as $user) {
            if ($user['email'] === $email) return $user;
        }
        return null;
    }

    public function createUser($userData) {
        $userData['id'] = count($this->data['users']) + 1;
        $this->data['users'][] = $userData;
        $this->saveData();
        return $userData;
    }

    public function addRecipe($recipeData) {
        $recipeData['id'] = count($this->data['recipes']) + 1;
        $this->data['recipes'][] = $recipeData;
        $this->saveData();
        return $recipeData;
    }
}
