# Couture — Object Reservation Platform for Sewing

**Couture** is a web platform built with **Symfony 6** that allows users to reserve sewing-related items (machines, accessories, etc.). The application supports different user roles (administrator, professional, visitor) and offers responsive design for both desktop and mobile use.

🔗 [Live demo](https://couture-nkq8.onrender.com/)

## Features

- Authentication system with role-based access (`ROLE_ADMIN`, `ROLE_PRO`, `ROLE_VISITEUR`)
- Add and manage reservable items
- Reservation system with confirmation form
- Admin/pro dashboard to view and manage items and reservations
- Responsive UI with mobile-specific cards and actions
- Filters by category and subcategory
- Image upload for each item

## Tech Stack

- PHP 8.2.12  
- Symfony 6  
- Doctrine ORM (SQLite)  
- Twig  
- CSS3  
- Hosted with Render

## Demo accounts

| Role          | Email                | Password       |
|---------------|----------------------|----------------|
| Admin         | admin@mail.com       | admin12345!    |
| Professional  | pro@mail.com         | pro12345!      |
| Visitor       | visiteur@mail.com    | visiteur12345! |

> These accounts are created using Fixtures.

## Deployment

Deployed on Render and available here: 
➡️ [https://couture-nkq8.onrender.com/](https://couture-nkq8.onrender.com/)
