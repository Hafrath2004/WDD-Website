<?php
// services.php

// You can include PHP logic here if needed in the future
// For now, it's a static page.

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Services - Ironix Hardware Shop</title>
  
  <!-- Google Font - Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Font Awesome CDN for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    /* Basic styling from index.php for consistency */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Poppins', Arial, sans-serif;
      background: #f8f9fa;
      color: #2c3e50;
      line-height: 1.6;
    }
    a { 
      text-decoration: none; 
      color: inherit; 
    }
    
    /* Navigation Bar - Copied from index.php */
    nav {
      background: #f8f9fa;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      padding: 10px 20px;
      border-bottom: 1px solid #e0e0e0;
    }
    .nav-container {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .nav-logo {
      font-size: 1.8em;
      font-weight: bold;
      color: #243b55;
    }
    
    /* Modified nav layout */
    .nav-links {
      display: flex;
      flex: 1;
      justify-content: center;
    }
    .nav-links ul {
      list-style: none;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: center;
      margin: 0;
      padding: 0;
      gap: 10px;
    }
    .nav-links ul li {
      margin: 0;
      display: flex;
      align-items: center;
      position: relative;
      height: 40px;
      line-height: 40px;
    }
    .nav-links ul li a,
    .cart-icon,
    .profile-icon {
      vertical-align: middle;
    }
    .nav-links ul li a {
      color: #243b55;
      padding: 8px 12px;
      transition: all 0.3s ease;
      font-weight: 500;
      text-transform: uppercase;
      font-size: 0.9em;
      position: relative;
    }
    .nav-links ul li a:hover {
      color: #f5a623;
    }
    /* Active menu item */
    .nav-links ul li a.active {
      color: #f5a623;
    }
    .nav-links ul li a.active:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 2px;
      background-color: #f5a623;
    }
    
    /* Add dropdown indicators */
    .dropdown-icon {
      margin-left: 5px;
      font-size: 10px;
    }
    
    /* Special styling for shop button */
    .shop-button {
      background-color: #f5a623;
      color: white !important;
      border-radius: 4px;
      padding: 8px 15px !important;
    }
    .shop-button:hover {
      background-color: #e09000;
    }
    
     /* Profile dropdown styles */
    .profile-dropdown {
      display: flex;
      align-items: center;
    }
    
    .profile-icon {
      color: #243b55;
      font-size: 1.4em;
      transition: all 0.3s ease;
      padding: 0 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      line-height: 1;
    }
    
    .profile-icon:hover {
      color: #f5a623;
      transform: scale(1.1);
    }
    
    .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      top: 100%;
      background-color: #fff;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;
      border-radius: 8px;
      overflow: hidden;
      margin-top: 5px;
    }
    
    .profile-dropdown:hover .dropdown-content {
      display: block;
    }
    
    .dropdown-content a {
      color: #243b55;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      text-align: left;
      transition: all 0.3s ease;
      border-bottom: 1px solid #e0e0e0;
    }
    
    .dropdown-content a i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }
    
    .dropdown-content a:last-child {
      border-bottom: none;
    }
    
    .dropdown-content a:hover {
      background-color: #f8f9fa;
      color: #f5a623;
    }
    
    /* Search Bar */
    .nav-search {
      margin-left: 20px;
      width: 300px;
      /* display: none; /* Hide in the top navigation */
    }
    .search-wrapper {
      position: relative;
      width: 200px;
      margin: 0 5px;
    }
    .search-wrapper i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #5d6d7e;
    }
    .search-wrapper input {
      width: 100%;
      padding: 10px 15px 10px 40px; /* extra left padding for the icon */
      border: 2px solid #5d6d7e;
      border-radius: 25px;
      background: #ffffff;
      color: #2c3e50;
      transition: all 0.3s ease;
    }
    .search-wrapper input:focus {
      border-color: #f5a623;
      box-shadow: 0 0 10px rgba(245,166,35,0.4);
      outline: none;
    }
    .search-wrapper input::placeholder {
      color: #95a5a6;
    }
    
    /* Navigation Icons */
    .nav-icons {
      display: flex;
      align-items: center;
      gap: 10px;
      position: relative;
      margin-left: 5px;
    }
    .nav-icons a {
      color: #243b55;
      font-size: 1.4em;
      transition: all 0.3s ease;
      padding: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .nav-icons a:hover {
      color: #f5a623;
      transform: scale(1.1);
    }
     /* Add icon-group and update profile-dropdown styles */
    .icon-group {
      display: flex;
      align-items: center;
      gap: 10px;
      position: relative;
    }

    .profile-dropdown {
      position: relative;
      display: flex;
      align-items: center;
    }
    
    .nav-vdivider {
      width: 4px;
      height: 40px;
      background: linear-gradient(180deg, #f5a623 0%, #f39c12 100%);
      margin: 0 18px;
      border-radius: 2px;
      align-self: center;
    }
     /* Add language dropdown styles */
    .language-dropdown {
      position: relative;
    }

    .language-icon {
      color: #243b55;
      font-size: 1.1em;
      padding: 8px 12px;
      border-radius: 20px;
      background: #f0f4f8;
      transition: all 0.3s ease;
    }

    .language-icon:hover {
      background: #f5a623;
      color: white;
    }

    .language-content {
      display: none;
      position: absolute;
      top: 100%; /* Always below the button */
      right: 0;
      background-color: #fff;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;
      border-radius: 8px;
      overflow: hidden;
      margin-top: 5px;
    }

    .language-content a {
      color: #243b55;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      text-align: left;
      transition: all 0.3s ease;
      border-bottom: 1px solid #e0e0e0;
    }

    .language-content a:last-child {
      border-bottom: none;
    }

    .language-content a:hover {
      background-color: #f8f9fa;
      color: #f5a623;
    }

    .language-content a i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }

    .language-dropdown:hover .language-content {
      display: block;
    }

    /* Footer styles - Copied from index.php */
    .footer-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      max-width: 1200px;
      margin: 0 auto;
      padding: 60px 20px;
      background: #f8f9fa;
      color: #2c3e50;
      border-top: 5px solid #141e30;
    }
    .footer-section {
      flex: 1;
      min-width: 220px;
      margin: 20px 15px;
    }
    .footer-section h2,
    .footer-section h3 {
      color: #141e30;
      margin-bottom: 20px;
      position: relative;
      display: inline-block;
    }
    .footer-section h2 { 
      font-size: 1.6em; 
    }
    .footer-section h2:after,
    .footer-section h3:after {
      content: '';
      display: block;
      width: 40px;
      height: 3px;
      background: #f5a623;
      margin-top: 10px;
    }
    .footer-section h3 { 
      font-size: 1.3em; 
    }
    .footer-section p {
      font-size: 0.95em;
      margin-bottom: 15px;
      color: #34495e;
      line-height: 1.6;
    }
    .footer-section ul { 
      list-style: none; 
    }
    .footer-section ul li { 
      margin-bottom: 12px; 
    }
    .footer-section ul li a {
      color: #34495e;
      font-size: 0.95em;
      transition: all 0.3s;
      position: relative;
      padding-left: 15px;
    }
    .footer-section ul li a:before {
      content: '→';
      position: absolute;
      left: 0;
      color: #5d6d7e;
      transition: all 0.3s;
    }
    .footer-section ul li a:hover { 
      color: #141e30;
      padding-left: 20px;
    }
    .footer-section ul li a:hover:before {
      color: #141e30;
    }
    .footer-section.subscribe form {
      display: flex;
      flex-direction: column;
    }
    .footer-section.subscribe input[type="email"] {
      padding: 14px;
      margin-bottom: 15px;
      border: 1px solid #e2e8f0;
      border-radius: 25px;
      background: #ffffff;
      transition: all 0.3s;
    }
    .footer-section.subscribe input[type="email"]:focus {
      border-color: #5d6d7e;
      box-shadow: 0 0 10px rgba(93,109,126,0.3);
      outline: none;
    }
    .footer-section.subscribe button {
      padding: 14px;
      border: none;
      background: linear-gradient(135deg, #141e30 0%, #243b55 100%);
      color: #fff;
      cursor: pointer;
      border-radius: 25px;
      font-size: 0.95em;
      font-weight: 600;
      transition: all 0.3s;
      box-shadow: 0 4px 10px rgba(20,30,48,0.2);
    }
    .footer-section.subscribe button:hover { 
      background: linear-gradient(135deg, #243b55 0%, #141e30 100%);
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(20,30,48,0.3);
    }
    .footer-bottom {
      background: #141e30;
      padding: 20px;
      text-align: center;
      font-size: 0.9em;
      color: #ffffff;
    }
    .footer-bottom .social-icons {
      margin-bottom: 15px;
    }
    .footer-bottom .social-icons a {
      margin: 0 10px;
      display: inline-flex;
      align-items: center;
      font-size: 1em;
      color: #f0f4f8;
      transition: all 0.3s;
    }
    .footer-bottom .social-icons a:hover {
      color: #f5a623;
      transform: translateY(-3px);
    }
    .footer-bottom .social-icons i {
      margin-right: 5px;
    }
    .footer-bottom a {
      color: #a0aec0;
      text-decoration: underline;
      transition: all 0.3s;
    }
    .footer-bottom a:hover {
      color: #f5a623;
    }
    
    /* Responsive Styles - Copied from index.php */
    @media (max-width: 768px) {
      .nav-container { 
        flex-direction: column; 
      }
      .nav-links ul { 
        flex-direction: column; 
        margin-top: 15px; 
        text-align: center;
      }
      .nav-links ul li {}
      .nav-search { 
        width: 100%;
        max-width: 100%;
        margin: 10px 0;
      }
      .nav-icons {
        justify-content: center;
        margin-top: 10px;
        width: 100%;
      }
      .banner-content h2 { 
        font-size: 2.4em; 
      }
      .banner-content p { 
        font-size: 1.2em; 
      }
      .footer-container { 
        flex-direction: column; 
        text-align: center; 
      }
      .footer-section h2:after,
      .footer-section h3:after {
        margin: 10px auto 0;
      }
      .footer-section ul li a {
        padding-left: 0;
      }
      .footer-section ul li a:before {
        content: none;
      }
      .footer-section ul li a:hover {
        padding-left: 0;
      }
    }
     @media (max-width: 768px) {
      .search-container {
        flex-direction: column;
        width: 100%;
        margin: 10px 0;
        gap: 15px;
      }
      
      .search-wrapper {
        width: 100%;
      }
      
      .nav-icons {
        justify-content: center;
        width: 100%;
      }
    }
     @media (max-width: 768px) {
      .language-content {
        right: auto;
        left: 50%;
        transform: translateX(-50%);
      }
    }
     @media (max-width: 768px) {
      .icon-wrapper {
        justify-content: center;
        width: 100%;
        margin: 10px 0;
      }
      
      .dropdown-content {
        position: static;
        width: 100%;
        margin-top: 10px;
      }
    }

    /* Styles specific to the Services page */
    .services-container {
        max-width: 900px;
        margin: 40px auto;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        padding: 30px;
    }
    .services-container h1 {
        text-align: center;
        color: #141e30;
        font-size: 2.2em;
        margin-bottom: 30px;
         position: relative;
         display: inline-block;
         width: 100%;
    }
    .services-container h1:after {
      content: '';
      display: block;
      width: 80px; 
      height: 3px;
      background: linear-gradient(90deg, transparent, #f5a623, transparent); 
      margin: 8px auto 0; 
    }
    .service-item {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    .service-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .service-item h3 {
        color: #f5a623; /* Gold color for service titles */
        font-size: 1.6em;
        margin-bottom: 10px;
    }
    .service-item p {
        color: #34495e;
        font-size: 1em;
        line-height: 1.6;
    }
    .promo-bar { background: #fff8e1; color: #d35400; text-align: center; font-size: 0.95em; padding: 7px 0; border-bottom: 1px solid #ffe0b2; letter-spacing: 0.5px; }
    .promo-bar span { margin: 0 18px; }
    .main-nav { background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 0; position: sticky; top: 0; z-index: 100; }
    .nav-inner { 
      max-width: 1200px; 
      margin: 0 auto; 
      display: flex; 
      align-items: center; 
      justify-content: space-between; 
      padding: 0 24px; 
      height: 68px; 
      gap: 16px; 
    }
    .logo { font-size: 2em; font-weight: 700; color: #222; letter-spacing: -1px; order: 0; flex-shrink: 0; }
    .nav-search { 
      flex: 1; 
      display: flex; 
      justify-content: center; 
      margin: 0 20px; 
      min-width: 0; 
      max-width: 300px;
      order: 1;
    }
    .search-box { position: relative; width: 270px; max-width: 100%; }
    .search-box input { width: 100%; padding: 11px 16px 11px 44px; border: 1.5px solid #ffe0b2; border-radius: 24px; background: #f9f9f9; font-size: 1em; transition: border 0.2s; }
    .search-box input:focus { border-color: #f5a623; background: #fff; outline: none; }
    .search-box i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #f5a623; font-size: 1.1em; }
    .nav-links { 
      display: flex; 
      align-items: center; 
      gap: 32px; 
      flex: 1; 
      justify-content: center; 
      order: 2;
      margin: 0 20px;
    }
    .nav-links a {
      text-transform: uppercase;
      font-weight: 600;
      font-size: 0.97em;
      color: #222;
      padding: 6px 8px;
      letter-spacing: 0.04em;
      transition: color 0.2s;
      position: relative;
      white-space: nowrap;
    }
    .nav-links a:hover, .nav-links a.active {
      color: #f5a623;
    }
    /* User and Cart Icons Section - Right Side */
    .nav-icons-section {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-left: auto;
      padding-left: 24px;
      border-left: 2px solid #e0e0e0;
      flex-shrink: 0;
      order: 3;
    }
    .nav-icon-wrapper {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .nav-icon-wrapper a {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
      color: #243b55;
      font-size: 1.3em;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      text-decoration: none;
      box-shadow: 0 3px 8px rgba(0,0,0,0.08);
      border: 2px solid #e0e0e0;
      position: relative;
    }
    .nav-icon-wrapper a::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(245,166,35,0.1);
      transform: translate(-50%, -50%);
      transition: width 0.3s, height 0.3s;
    }
    .nav-icon-wrapper a:hover::before {
      width: 100%;
      height: 100%;
    }
    .nav-icon-wrapper a:hover {
      background: linear-gradient(135deg, #f5a623 0%, #f39c12 100%);
      color: #fff;
      transform: translateY(-2px) scale(1.1);
      box-shadow: 0 6px 16px rgba(245,166,35,0.4);
      border-color: #f5a623;
    }
    .nav-icon-wrapper a i {
      position: relative;
      z-index: 1;
    }
    .profile-dropdown {
      position: relative;
      display: inline-block;
    }
    .profile-dropdown .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      top: calc(100% + 10px);
      background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
      min-width: 240px;
      box-shadow: 0 12px 40px rgba(0,0,0,0.15), 0 4px 12px rgba(245,166,35,0.1);
      border-radius: 16px;
      overflow: hidden;
      z-index: 1000;
      border: 2px solid #f5a623;
      animation: dropdownFadeIn 0.3s ease-out;
      transform-origin: top right;
    }
    @keyframes dropdownFadeIn {
      from {
        opacity: 0;
        transform: translateY(-10px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }
    .profile-dropdown:hover .dropdown-content {
      display: block;
    }
    .dropdown-header {
      background: linear-gradient(135deg, #f5a623 0%, #f39c12 100%);
      padding: 20px;
      text-align: center;
      color: #fff;
      border-bottom: 2px solid rgba(255,255,255,0.2);
    }
    .dropdown-header i {
      font-size: 2.5em;
      margin-bottom: 8px;
      display: block;
      text-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .dropdown-header h3 {
      margin: 0;
      font-size: 1.1em;
      font-weight: 700;
      letter-spacing: 0.5px;
    }
    .dropdown-header p {
      margin: 6px 0 0 0;
      font-size: 0.85em;
      opacity: 0.95;
      font-weight: 400;
    }
    .profile-dropdown .dropdown-content a {
      color: #243b55;
      padding: 18px 24px;
      display: flex;
      align-items: center;
      gap: 16px;
      font-size: 1.05em;
      font-weight: 600;
      border-bottom: 1px solid #f0f0f0;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }
    .profile-dropdown .dropdown-content a::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      width: 4px;
      height: 100%;
      background: linear-gradient(180deg, #f5a623 0%, #f39c12 100%);
      transform: scaleY(0);
      transition: transform 0.3s ease;
    }
    .profile-dropdown .dropdown-content a:hover::before {
      transform: scaleY(1);
    }
    .profile-dropdown .dropdown-content a:last-child {
      border-bottom: none;
    }
    .profile-dropdown .dropdown-content a:hover {
      background: linear-gradient(135deg, #fff8e1 0%, #ffe0b2 100%);
      color: #f5a623;
      padding-left: 28px;
      transform: translateX(4px);
    }
    .profile-dropdown .dropdown-content a i {
      font-size: 1.3em;
      width: 28px;
      text-align: center;
      color: #f5a623;
      transition: all 0.3s ease;
    }
    .profile-dropdown .dropdown-content a:hover i {
      color: #f39c12;
      transform: scale(1.15) rotate(5deg);
    }
    .dropdown-footer {
      padding: 12px;
      text-align: center;
      background: #f8f9fa;
      border-top: 1px solid #e0e0e0;
      font-size: 0.8em;
      color: #888;
    }
    /* Notification Dropdown Styles */
    .notification-dropdown {
      position: relative;
      display: inline-block;
    }
    .notification-dropdown .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      top: calc(100% + 10px);
      background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
      width: 320px;
      max-height: 420px;
      box-shadow: 0 12px 40px rgba(0,0,0,0.15), 0 4px 12px rgba(245,166,35,0.1);
      border-radius: 16px;
      overflow: hidden;
      z-index: 1000;
      border: 2px solid #f5a623;
      animation: dropdownFadeIn 0.3s ease-out;
      transform-origin: top right;
    }
    .notification-dropdown.active .dropdown-content {
      display: block;
    }
    .notification-header {
      background: linear-gradient(135deg, #f5a623 0%, #f39c12 100%);
      padding: 16px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: #fff;
      border-bottom: 2px solid rgba(255,255,255,0.2);
    }
    .notification-header h3 {
      margin: 0;
      font-size: 1.1em;
      font-weight: 700;
      letter-spacing: 0.5px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .notification-header h3 i {
      font-size: 1.2em;
    }
    .notification-header .mark-all-read {
      background: rgba(255,255,255,0.2);
      border: 1px solid rgba(255,255,255,0.3);
      color: #fff;
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 0.85em;
      cursor: pointer;
      transition: all 0.3s;
      font-weight: 600;
    }
    .notification-header .mark-all-read:hover {
      background: rgba(255,255,255,0.3);
      transform: scale(1.05);
    }
    .notification-list {
      max-height: 320px;
      overflow-y: auto;
      background: #fff;
    }
    .notification-list::-webkit-scrollbar {
      width: 6px;
    }
    .notification-list::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    .notification-list::-webkit-scrollbar-thumb {
      background: #f5a623;
      border-radius: 3px;
    }
    .notification-item {
      padding: 14px 18px;
      border-bottom: 1px solid #f0f0f0;
      display: flex;
      align-items: flex-start;
      gap: 12px;
      transition: all 0.3s;
      cursor: pointer;
      position: relative;
    }
    .notification-item.unread {
      background: linear-gradient(90deg, #fff8e1 0%, #ffffff 100%);
      border-left: 3px solid #f5a623;
    }
    .notification-item.unread::before {
      content: '';
      position: absolute;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 8px;
      height: 8px;
      background: #f5a623;
      border-radius: 50%;
    }
    .notification-item:hover {
      background: linear-gradient(135deg, #fff8e1 0%, #ffe0b2 100%);
      transform: translateX(2px);
    }
    .notification-item:last-child {
      border-bottom: none;
    }
    .notification-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2em;
      flex-shrink: 0;
      background: linear-gradient(135deg, #f5a623 0%, #f39c12 100%);
      color: #fff;
      box-shadow: 0 2px 8px rgba(245,166,35,0.3);
    }
    .notification-icon.order { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); }
    .notification-icon.discount { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); }
    .notification-icon.shipping { background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); }
    .notification-icon.info { background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); }
    .notification-content {
      flex: 1;
      min-width: 0;
    }
    .notification-content h4 {
      margin: 0 0 4px 0;
      font-size: 0.95em;
      font-weight: 700;
      color: #222;
      line-height: 1.3;
    }
    .notification-content p {
      margin: 0;
      font-size: 0.85em;
      color: #666;
      line-height: 1.4;
    }
    .notification-time {
      font-size: 0.75em;
      color: #999;
      margin-top: 4px;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .notification-footer {
      padding: 12px;
      text-align: center;
      background: #f8f9fa;
      border-top: 1px solid #e0e0e0;
    }
    .notification-footer a {
      color: #f5a623;
      font-weight: 600;
      font-size: 0.9em;
      text-decoration: none;
      transition: color 0.3s;
    }
    .notification-footer a:hover {
      color: #f39c12;
    }
    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
      color: #fff;
      font-size: 0.65em;
      font-weight: 700;
      padding: 4px 7px;
      border-radius: 12px;
      min-width: 20px;
      text-align: center;
      box-shadow: 0 2px 8px rgba(231,76,60,0.5);
      border: 2px solid #fff;
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }
    .notification-empty {
      padding: 40px 20px;
      text-align: center;
      color: #999;
    }
    .notification-empty i {
      font-size: 3em;
      margin-bottom: 12px;
      color: #ddd;
    }
    .notification-empty p {
      margin: 0;
      font-size: 0.95em;
    }
  </style>
  <style>
    /* Dark Mode Styles */
    body.dark-mode {
      background: #1a202c;
      color: #e2e8f0;
    }
    .dark-mode nav {
      background: #2d3748;
      box-shadow: 0 1px 3px rgba(0,0,0,0.3);
      border-bottom-color: #4a5568;
    }
    .dark-mode .nav-logo {
      color: #e2e8f0;
    }
    .dark-mode .nav-links ul li a {
      color: #e2e8f0;
    }
    .dark-mode .nav-links ul li a:hover {
      color: #f5a623;
    }
    .dark-mode .nav-vdivider {
      background: linear-gradient(180deg, #f5a623 0%, #f39c12 100%); /* Keep accent color */
    }
    .dark-mode .search-wrapper input {
      border-color: #4a5568;
      background: #2d3748;
      color: #e2e8f0;
    }
     .dark-mode .search-wrapper i {
        color: #a0aec0;
     }
    .dark-mode .search-wrapper input::placeholder {
      color: #a0aec0;
    }
    .dark-mode .nav-icons a {
      color: #e2e8f0;
    }
    .dark-mode .nav-icons a:hover {
      color: #f5a623;
    }
    .dark-mode .profile-icon {
        color: #e2e8f0;
    }
    .dark-mode .profile-icon:hover {
        color: #f5a623;
    }
    .dark-mode .dropdown-content {
        background: linear-gradient(180deg, #2d3748 0%, #1a202c 100%);
        box-shadow: 0 12px 40px rgba(0,0,0,0.5), 0 4px 12px rgba(245,166,35,0.2);
        border-color: #f5a623;
    }
    .dark-mode .dropdown-header {
        background: linear-gradient(135deg, #f5a623 0%, #f39c12 100%);
    }
    .dark-mode .dropdown-content a {
        color: #e2e8f0;
        border-bottom-color: #4a5568;
    }
    .dark-mode .dropdown-content a:hover {
        background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
        color: #f5a623;
    }
    .dark-mode .dropdown-footer {
        background: #1a202c;
        border-top-color: #4a5568;
        color: #a0aec0;
    }
    .dark-mode .language-icon {
      color: #e2e8f0;
      background: #4a5568;
    }
     .dark-mode .language-icon:hover {
        background: #f5a623;
        color: #1a202c;
     }
    .dark-mode .language-content {
      background-color: #2d3748;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.5);
    }
    .dark-mode .language-content a {
      color: #e2e8f0;
      border-bottom-color: #4a5568;
    }
    .dark-mode .language-content a:hover {
      background-color: #4a5568;
      color: #f5a623;
    }
    /* Styles specific to the Services page */
    .dark-mode .services-container {
        background: #2d3748;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
     .dark-mode .services-container h1 {
        color: #e2e8f0;
     }
    .dark-mode .service-item {
        background: #4a5568;
        border-bottom-color: #718096;
    }
     .dark-mode .service-item h2 {
        color: #f5a623; /* Use accent color for service titles */
     }
     .dark-mode .service-item p {
        color: #a0aec0;
     }
     /* Footer styles for dark mode */
    .dark-mode .footer-container {
        background: #2d3748;
        color: #e2e8f0;
        border-top-color: #f5a623; /* Use accent color for footer top */
    }
    .dark-mode .footer-section h2,
    .dark-mode .footer-section h3 {
        color: #f5a623; /* Use accent color for footer headings */
    }
     .dark-mode .footer-section h2:after,
     .dark-mode .footer-section h3:after {
        background: #e2e8f0; /* Lighter line in dark mode */
     }
    .dark-mode .footer-section p {
        color: #a0aec0;
    }
    .dark-mode .footer-section ul li a {
        color: #a0aec0;
    }
    .dark-mode .footer-section ul li a:before {
        color: #718096;
    }
    .dark-mode .footer-section ul li a:hover {
        color: #f5a623;
    }
    .dark-mode .footer-section ul li a:hover:before {
        color: #f5a623;
    }
     .dark-mode .footer-section.subscribe input[type="email"] {
        border-color: #718096;
        background: #2d3748;
        color: #e2e8f0;
     }
     .dark-mode .footer-section.subscribe input[type="email"]:focus {
        border-color: #f5a623;
        box-shadow: 0 0 10px rgba(245,166,35,0.4);
     }
     .dark-mode .footer-section.subscribe button {
        background: linear-gradient(135deg, #f5a623 0%, #f39c12 100%); /* Use accent color gradient */
        color: #1a202c;
        box-shadow: 0 4px 10px rgba(245,166,35,0.3);
     }
     .dark-mode .footer-section.subscribe button:hover {
        background: linear-gradient(135deg, #f39c12 0%, #f5a623 100%);
        box-shadow: 0 6px 12px rgba(245,166,35,0.4);
     }
    .dark-mode .footer-bottom {
        background: #1a202c;
        color: #a0aec0;
    }
     .dark-mode .footer-bottom .social-icons a {
        color: #a0aec0;
     }
     .dark-mode .footer-bottom .social-icons a:hover {
        color: #f5a623;
     }
    .dark-mode .footer-bottom a {
        color: #718096;
    }
    .dark-mode .footer-bottom a:hover {
        color: #f5a623;
    }

  </style>
</head>
<body>
  <div class="promo-bar">
    <span><i class="fas fa-truck"></i> Free shipping on all orders</span>
    <span><i class="fas fa-undo"></i> Free returns <b>Up to 90 days*</b></span>
  </div>
  <nav class="main-nav">
    <div class="nav-inner">
      <a href="index.php" class="logo">IRONIX</a>
      <div class="nav-search">
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Search for products..." onkeypress="if(event.key==='Enter'){window.location.href='search_results.php?query='+encodeURIComponent(this.value);}">
        </div>
      </div>
      <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="features.php">Features</a>
        <a href="services.php" class="active">Services</a>
        <a href="blog.php">Blog</a>
        <a href="contact.php">Contact</a>
        <a href="support.php">Support</a>
      </div>
      <!-- User and Cart Icons - Right Side -->
      <div class="nav-icons-section">
        <div class="nav-icon-wrapper profile-dropdown">
          <a href="#" title="Account">
            <i class="fas fa-user-circle"></i>
          </a>
          <div class="dropdown-content">
            <div class="dropdown-header">
              <i class="fas fa-user-circle"></i>
              <h3>Welcome to IRONIX</h3>
              <p>Sign in to your account</p>
            </div>
            <a href="login.html"><i class="fas fa-sign-in-alt"></i> <span>Login to Account</span></a>
            <a href="registration.php"><i class="fas fa-user-plus"></i> <span>Create New Account</span></a>
            <div class="dropdown-footer">
              <i class="fas fa-shield-alt"></i> Secure & Fast
            </div>
          </div>
        </div>
        <div class="nav-icon-wrapper notification-dropdown">
          <a href="#" title="Notifications" class="notification-toggle">
            <i class="fas fa-bell"></i>
            <span class="notification-badge" id="notificationBadge">3</span>
          </a>
          <div class="dropdown-content">
            <div class="notification-header">
              <h3><i class="fas fa-bell"></i> Notifications</h3>
              <button class="mark-all-read" onclick="markAllAsRead()">Mark all read</button>
            </div>
            <div class="notification-list" id="notificationList">
              <div class="notification-item unread" onclick="markAsRead(this)">
                <div class="notification-icon order">
                  <i class="fas fa-box"></i>
                </div>
                <div class="notification-content">
                  <h4>Order Shipped!</h4>
                  <p>Your order #12345 has been shipped and is on its way.</p>
                  <div class="notification-time">
                    <i class="far fa-clock"></i> 2 hours ago
                  </div>
                </div>
              </div>
              <div class="notification-item unread" onclick="markAsRead(this)">
                <div class="notification-icon discount">
                  <i class="fas fa-tag"></i>
                </div>
                <div class="notification-content">
                  <h4>Special Discount!</h4>
                  <p>Get 25% off on all power tools. Limited time offer!</p>
                  <div class="notification-time">
                    <i class="far fa-clock"></i> 5 hours ago
                  </div>
                </div>
              </div>
              <div class="notification-item unread" onclick="markAsRead(this)">
                <div class="notification-icon shipping">
                  <i class="fas fa-truck"></i>
                </div>
                <div class="notification-content">
                  <h4>Free Shipping Available</h4>
                  <p>Your cart qualifies for free shipping. Add more items!</p>
                  <div class="notification-time">
                    <i class="far fa-clock"></i> 1 day ago
                  </div>
                </div>
              </div>
              <div class="notification-item" onclick="markAsRead(this)">
                <div class="notification-icon info">
                  <i class="fas fa-info-circle"></i>
                </div>
                <div class="notification-content">
                  <h4>Welcome to Ironix!</h4>
                  <p>Thank you for joining us. Explore our amazing products.</p>
                  <div class="notification-time">
                    <i class="far fa-clock"></i> 3 days ago
                  </div>
                </div>
              </div>
            </div>
            <div class="notification-footer">
              <a href="#">View All Notifications</a>
            </div>
          </div>
        </div>
        <div class="nav-icon-wrapper">
          <a href="cart.php" title="Shopping Cart">
            <i class="fas fa-shopping-cart"></i>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <div class="services-container">
    <h1>Our Services</h1>

    <div class="service-item">
      <h2>Tool Rental</h2>
      <p>Rent a wide selection of high-quality tools for your projects, from power tools to specialized equipment, on a daily or weekly basis.</p>
    </div>

    <div class="service-item">
      <h2>Key Cutting &amp; Duplication</h2>
      <p>Accurate and fast key cutting services for a variety of key types, including house keys, car keys, and padlock keys.</p>
    </div>

    <div class="service-item">
      <h2>Paint Mixing &amp; Matching</h2>
      <p>Bring in a sample and we can mix paint to match any color, or choose from our wide range of available colors and finishes.</p>
    </div>

    <div class="service-item">
      <h2>Blade Sharpening</h2>
      <p>Professional sharpening services for saw blades, knives, scissors, and other tools to keep them performing at their best.</p>
    </div>

    <div class="service-item">
      <h2>Hardware Consultation</h2>
      <p>Expert advice and guidance from our experienced staff to help you find the right tools and materials for your specific needs.</p>
    </div>
  </div>

  <footer style="background:#181818; color:#fff; padding:48px 0 24px 0; margin-top:48px; font-family:'Poppins',Arial,sans-serif;">
    <div style="max-width:1200px; margin:0 auto; display:flex; flex-wrap:wrap; justify-content:space-between; gap:32px;">
      <div style="flex:1 1 180px; min-width:180px; margin-bottom:24px;">
        <h4 style="font-size:1.1em; font-weight:700; margin-bottom:18px;">Company info</h4>
        <div style="font-size:0.98em; line-height:2; color:#ccc;">
          <div><a href="#" style="color:#fff;">About Ironix</a></div>
          <div><a href="#" style="color:#fff;">Ironix – Shop Like a Pro</a></div>
          <div><a href="#" style="color:#fff;">Contact us</a></div>
          <div><a href="#" style="color:#fff;">Careers</a></div>
          <div><a href="#" style="color:#fff;">Press</a></div>
          <div><a href="#" style="color:#fff;">Ironix's Tree Planting Program</a></div>
        </div>
      </div>
      <div style="flex:1 1 180px; min-width:180px; margin-bottom:24px;">
        <h4 style="font-size:1.1em; font-weight:700; margin-bottom:18px;">Customer service</h4>
        <div style="font-size:0.98em; line-height:2; color:#ccc;">
          <div><a href="#" style="color:#fff;">Return and refund policy</a></div>
          <div><a href="#" style="color:#fff;">Intellectual property policy</a></div>
          <div><a href="#" style="color:#fff;">Shipping info</a></div>
          <div><a href="#" style="color:#fff;">Report suspicious activity</a></div>
        </div>
      </div>
      <div style="flex:1 1 180px; min-width:180px; margin-bottom:24px;">
        <h4 style="font-size:1.1em; font-weight:700; margin-bottom:18px;">Help</h4>
        <div style="font-size:0.98em; line-height:2; color:#ccc;">
          <div><a href="#" style="color:#fff;">Support center & FAQ</a></div>
          <div><a href="#" style="color:#fff;">Safety center</a></div>
          <div><a href="#" style="color:#fff;">Ironix purchase protection</a></div>
          <div><a href="#" style="color:#fff;">Sitemap</a></div>
          <div><a href="#" style="color:#fff;">Partner with Ironix</a></div>
        </div>
      </div>
      <div style="flex:1 1 260px; min-width:260px; margin-bottom:24px;">
        <h4 style="font-size:1.1em; font-weight:700; margin-bottom:18px;">Download the Ironix App</h4>
        <div style="font-size:0.98em; line-height:2; color:#ccc; margin-bottom:18px;">
          <span style="display:inline-block; margin-right:12px;"><i class="fas fa-bell" style="color:#f5a623;"></i> Price-drop alerts</span>
          <span style="display:inline-block; margin-right:12px;"><i class="fas fa-truck" style="color:#00b894;"></i> Track orders any time</span><br>
          <span style="display:inline-block; margin-right:12px;"><i class="fas fa-shield-alt" style="color:#0984e3;"></i> Secure checkout</span>
          <span style="display:inline-block; margin-right:12px;"><i class="fas fa-tags" style="color:#e17055;"></i> Exclusive offers</span>
        </div>
        <div style="display:flex; gap:16px; margin-bottom:18px;">
          <a href="#" style="display:inline-block; background:#fff; color:#000; border-radius:24px; padding:8px 18px; font-weight:600; font-size:1em; text-decoration:none; box-shadow:0 2px 8px rgba(0,0,0,0.08);"><i class="fab fa-apple" style="color:#000; font-size:1.2em; margin-right:8px;"></i>App Store</a>
          <a href="#" style="display:inline-block; background:#fff; color:#000; border-radius:24px; padding:8px 18px; font-weight:600; font-size:1em; text-decoration:none; box-shadow:0 2px 8px rgba(0,0,0,0.08);"><i class="fab fa-google-play" style="color:#34a853; font-size:1.2em; margin-right:8px;"></i>Google Play</a>
        </div>
        <div style="margin-top:18px;">
          <span style="font-weight:600; color:#fff;">Connect with Ironix</span>
          <div style="margin-top:10px; display:flex; gap:18px;">
            <a href="#" title="Instagram"><i class="fab fa-instagram" style="color:#e1306c; font-size:1.5em;"></i></a>
            <a href="#" title="Facebook"><i class="fab fa-facebook" style="color:#1877f3; font-size:1.5em;"></i></a>
            <a href="#" title="X"><i class="fab fa-x-twitter" style="color:#fff; font-size:1.5em;"></i></a>
            <a href="#" title="TikTok"><i class="fab fa-tiktok" style="color:#fff; font-size:1.5em;"></i></a>
            <a href="#" title="YouTube"><i class="fab fa-youtube" style="color:#ff0000; font-size:1.5em;"></i></a>
            <a href="#" title="Pinterest"><i class="fab fa-pinterest" style="color:#e60023; font-size:1.5em;"></i></a>
          </div>
        </div>
      </div>
    </div>
    <div style="text-align:center; color:#aaa; font-size:0.95em; margin-top:32px;">&copy; 2025 Ironix Hardware Shop. All Rights Reserved.</div>
  </footer>

  <script>
    // Mode toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
      const modeToggleBtn = document.getElementById('modeToggle');
      const body = document.body;

      // Check for saved mode in localStorage
      const currentMode = localStorage.getItem('themeMode');
      if (currentMode) {
        body.classList.add(currentMode);
        if (currentMode === 'dark-mode') {
          modeToggleBtn.textContent = 'Light Mode';
        } else {
          modeToggleBtn.textContent = 'Dark Mode';
        }
      }

      modeToggleBtn.addEventListener('click', function() {
        if (body.classList.contains('dark-mode')) {
          body.classList.remove('dark-mode');
          localStorage.setItem('themeMode', 'light-mode');
          modeToggleBtn.textContent = 'Dark Mode';
        } else {
          body.classList.add('dark-mode');
          localStorage.setItem('themeMode', 'dark-mode');
          modeToggleBtn.textContent = 'Light Mode';
        }
      });
    });

    // Language dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
      var langBtn = document.querySelector('.language-dropdown > a.language-icon');
      var langMenu = document.querySelector('.language-dropdown .language-content');

      if (langBtn && langMenu) {
        langBtn.addEventListener('click', function(e) {
          e.preventDefault();
          langMenu.style.display = (langMenu.style.display === 'block') ? 'none' : 'block';
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
          if (!langBtn.contains(e.target) && !langMenu.contains(e.target)) {
            langMenu.style.display = 'none';
          }
        });
      }
    });

    // Add event listener to user icon to redirect to login.html
    document.addEventListener('DOMContentLoaded', function() {
      var userProfileIcon = document.getElementById('userProfileIcon');
      if (userProfileIcon) {
        userProfileIcon.addEventListener('click', function(e) {
          e.preventDefault();
          window.location.href = 'login.html';
        });
      }
    });

    // Add event listener to the search input
    const searchInput = document.querySelector('.search-wrapper input[type="text"]');
    if (searchInput) {
      searchInput.addEventListener('keypress', function(event) {
        // Check if the key pressed was Enter (key code 13)
        if (event.key === 'Enter') {
          event.preventDefault(); // Prevent default form submission
          const searchQuery = searchInput.value.trim();
          if (searchQuery) {
            // Redirect to search_results.php with query parameter
            window.location.href = 'search_results.php?query=' + encodeURIComponent(searchQuery);
          }
        }
      });
    }
    
    // Handle profile dropdown click
    document.addEventListener('DOMContentLoaded', function() {
      const profileDropdown = document.querySelector('.profile-dropdown');
      const dropdownToggle = profileDropdown?.querySelector('a');
      const dropdownContent = profileDropdown?.querySelector('.dropdown-content');
      
      if (dropdownToggle && dropdownContent) {
        dropdownToggle.addEventListener('click', function(e) {
          e.preventDefault();
          profileDropdown.classList.toggle('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
          if (!profileDropdown.contains(e.target)) {
            profileDropdown.classList.remove('active');
          }
        });
        
        // Prevent dropdown from closing when clicking inside it
        dropdownContent.addEventListener('click', function(e) {
          e.stopPropagation();
        });
      }
      
      // Handle notification dropdown click
      const notificationDropdown = document.querySelector('.notification-dropdown');
      const notificationToggle = notificationDropdown?.querySelector('.notification-toggle');
      const notificationDropdownContent = notificationDropdown?.querySelector('.dropdown-content');
      
      if (notificationToggle && notificationDropdownContent) {
        notificationToggle.addEventListener('click', function(e) {
          e.preventDefault();
          notificationDropdown.classList.toggle('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
          if (!notificationDropdown.contains(e.target)) {
            notificationDropdown.classList.remove('active');
          }
        });
        
        // Prevent dropdown from closing when clicking inside it
        notificationDropdownContent.addEventListener('click', function(e) {
          e.stopPropagation();
        });
      }
      
      // Update notification badge count - make it globally accessible
      window.updateNotificationBadge = function() {
        const unreadCount = document.querySelectorAll('.notification-item.unread').length;
        const badge = document.getElementById('notificationBadge');
        if (badge) {
          if (unreadCount > 0) {
            badge.textContent = unreadCount;
            badge.style.display = 'block';
          } else {
            badge.style.display = 'none';
          }
        }
      };
      
      // Mark notification as read
      window.markAsRead = function(element) {
        element.classList.remove('unread');
        window.updateNotificationBadge();
      };
      
      // Mark all notifications as read
      window.markAllAsRead = function() {
        const unreadItems = document.querySelectorAll('.notification-item.unread');
        unreadItems.forEach(item => {
          item.classList.remove('unread');
        });
        window.updateNotificationBadge();
      };
      
      // Initialize badge count
      window.updateNotificationBadge();
    });
  </script>

</body>
</html> 