# Tastebite About Page - Desktop Implementation

This is a pixel-perfect HTML, CSS, and JavaScript implementation of the "About Desktop" section from the Tastebite Figma design.

## Files Structure

```
about-page/
├── index.html          # Main HTML structure
├── styles.css          # Complete styling with responsive design
├── script.js           # Interactive functionality
├── images/             # Image assets (to be downloaded)
└── README.md          # This file
```

## Required Images

The following images need to be downloaded from the Figma file and placed in the `images/` directory:

### Team Member Avatars (JPEG/PNG format):
- `team-1.jpg` through `team-12.jpg`

### Section Images (JPG format):
- `section-01.jpg` - Section about "We're a group of foodies..."
- `section-02.jpg` - Section about "Simple, Easy Recipes..."

### Logo (SVG/PNG format):
- `logo.png` or `logo.svg`

## How to Download Images from Figma

1. Open the Figma file: https://www.figma.com/design/1lWLYX72dRKzOqfWgNFX4L/Tastebite--Figma-?node-id=0-284&t=gZyb3fCqcvXpc77p-0
2. Navigate to node `0:284` (About Desktop)
3. Export images from the relevant frames/components

Alternatively, you can use the Figma MCP server to download images programmatically (requires MCP server setup with Figma API token).

## Features

### Layout & Design
- Pixel-perfect recreation of the Figma design
- Responsive design for desktop, tablet, and mobile
- Exact typography matching (Playfair Display + Inter fonts)
- Proper spacing and alignment

### Sections Included
1. **Navigation Bar** - Logo, menu items, and icons
2. **Header** - "About" title with separator line
3. **Content Section 01** - Food quality description with overlaid text
4. **Content Section 02** - Recipes section with image
5. **Team Section** - 12 team member cards in grid layout
6. **Footer Note** - Contact info and social icons
7. **Main Footer** - Links, logo, and bottom social icons

### Interactive Features
- Mobile-responsive navigation with hamburger menu
- Social media icon hover effects
- Team member card hover animations
- Smooth scrolling navigation
- Dropdown menu placeholders
- Accessibility improvements (keyboard navigation)

## Browser Support
- Chrome 70+
- Firefox 65+
- Safari 12+
- Edge 79+

## Setup Instructions

1. Download or clone this repository
2. Download the required images from Figma and place them in the `images/` directory
3. Open `index.html` in your web browser

## Font Loading
The project uses Google Fonts:
- Playfair Display (700 weight) for headings
- Inter (400, 500, 600 weights) for body text

## Customization
- Colors are defined in CSS custom properties
- Breakpoints: 1200px, 768px, 480px
- Font sizes and spacing match the original design

## Notes
- JavaScript includes placeholder functionality for social media links
- Some interactive elements (dropdown menus) are prepared for future expansion
- The design includes precise measurements from the Figma file
- Mobile layout adapts the grid and typography appropriately
