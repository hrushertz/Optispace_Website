# Solutions OptiSpace - Lean Factory Building Website

A professional PHP website for Solutions OptiSpace, showcasing their Lean Factory Building (LFB) methodology and services.

## Features

- **Responsive Design**: Works seamlessly on desktop, tablet, and mobile devices
- **Clean Architecture**: Organized file structure with reusable components
- **Modern UI**: Professional design with smooth animations and interactions
- **SEO Friendly**: Proper meta tags, semantic HTML, and clean URLs
- **Easy Navigation**: Intuitive menu structure and clear call-to-actions
- **Contact Forms**: Pulse Check request form and general inquiry form

## File Structure

```
/project
├── index.php                 # Home page
├── philosophy.php            # LFB Philosophy page
├── process.php               # Our Process page
├── portfolio.php             # Portfolio & Resources page
├── about.php                 # About Us page
├── contact.php               # Contact page with forms
├── services/
│   ├── greenfield.php        # Greenfield projects page
│   ├── brownfield.php        # Brownfield projects page
│   └── post-commissioning.php # Post-commissioning support page
├── includes/
│   ├── header.php            # Shared header component
│   └── footer.php            # Shared footer component
├── assets/
│   ├── css/
│   │   └── style.css         # Main stylesheet
│   └── js/
│       └── main.js           # JavaScript interactions
├── .htaccess                 # Apache configuration for clean URLs
└── README.md                 # This file
```

## Pages Overview

### Home Page (index.php)
- Hero section with main value proposition
- LFB concept introduction
- Stats and trust signals
- Services overview
- Waste elimination explanation
- CTA sections

### Philosophy Page
- Detailed LFB methodology explanation
- Inside-out design approach
- Comparison with conventional architects
- ROI and cost prevention information

### Services Pages
- **Greenfield**: New factory design services
- **Brownfield**: Existing factory optimization
- **Post-Commissioning**: Ongoing support services

### Process Page
- Four-phase engagement methodology
- Detailed breakdown of each phase
- Timeline and investment structure

### Portfolio Page
- Industries served
- Sample designs and drawings
- Innovations and success stories
- Before/after transformations

### About Page
- Company background
- Leadership profile (Minish Umrani)
- Team and associates
- Values and approach

### Contact Page
- Pulse Check request form
- Office locations
- General inquiry form
- FAQs

## Setup Instructions

1. **Server Requirements**
   - PHP 7.4 or higher
   - Apache web server with mod_rewrite enabled
   - No database required (static site)

2. **Installation**
   - Upload all files to your web server
   - Ensure the .htaccess file is in the root directory
   - Make sure mod_rewrite is enabled in Apache

3. **Configuration**
   - Update contact information in `includes/footer.php`
   - Update office addresses in `contact.php`
   - Replace placeholder contact details with actual information

## Customization

### Colors
The color scheme is defined in CSS variables in `assets/css/style.css`:
- Primary Color: #0066cc (Blue)
- Secondary Color: #00a86b (Green)
- Accent Color: #ff6b35 (Orange)

### Content Updates
- Page content can be edited directly in each PHP file
- For consistent header/footer changes, edit `includes/header.php` and `includes/footer.php`

### Forms
Currently, forms are set up with client-side JavaScript. To enable actual form submission:
1. Create a PHP form handler (e.g., `process-form.php`)
2. Update form action attributes
3. Implement email sending or database storage logic

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Performance Optimizations

- CSS and JS minification recommended for production
- Image optimization recommended
- Gzip compression enabled via .htaccess
- Browser caching configured

## Future Enhancements

Potential additions for future versions:
- Blog/articles section
- Case study details pages
- Client testimonials with photos
- Video content integration
- Multi-language support
- Admin panel for content management

## Support

For any questions or issues, please contact the development team or Solutions OptiSpace directly.

## License

Proprietary - © Solutions OptiSpace. All rights reserved.
