# 🌱 Smart Cultivation System  
### AI-Ready Smart Agriculture Management Platform  

[![Live Demo](https://img.shields.io/badge/Live-Demo-green?style=for-the-badge&logo=google-chrome&logoColor=white)](https://smart-cultivation.kesug.com)  
[![Tech](https://img.shields.io/badge/Tech-Full%20Stack%20%7C%20Scalable-blueviolet?style=for-the-badge)](#)
<img width="1883" height="758" alt="Smart Cultivation System Dashboard" src="https://github.com/user-attachments/assets/593afbe2-e835-441e-ab2c-5b3a4adf925f" />

---

## 🚀 Overview  

**Smart Cultivation System** is a full-stack agriculture management platform designed to help farmers and administrators digitize farm operations, automate crop monitoring, and streamline agricultural workflows.

The system provides real-time crop tracking, intelligent notifications, knowledge-based guidance, and role-based dashboards — making agriculture data-driven, efficient, and scalable.

---

## 🌐 Live Demo  

🔗 https://smart-cultivation.kesug.com  

---

## 🏗 System Architecture  


┌─────────────────────┐        HTTP Requests        ┌────────────────────────┐
│   Farmer Dashboard  │ ─────────────────────────▶ │                        │
└─────────────────────┘                             │                        │
                                                     │                        │
┌─────────────────────┐        HTTP Requests        │     PHP Backend        │
│    Admin Dashboard  │ ─────────────────────────▶ │  (Business Logic +     │
└─────────────────────┘                             │   Authentication +     │
                                                     │   Access Control)     │
                                                     │                        │
                                                     └───────────┬────────────┘
                                                                 │
                                                                 │ SQL Queries
                                                                 ▼
                                                     ┌────────────────────────┐
                                                     │     MySQL Database     │
                                                     │   (Persistent Storage) │
                                                     └────────────────────────┘
                                                                 │
                                                                 │ Event Triggers
                                                                 ▼
                                                     ┌────────────────────────┐
                                                     │   PHPMailer Service    │
                                                     │ (SMTP Email Delivery)  │
                                                     └────────────────────────┘


---

## 🎯 Key Features  

### 👨‍🌾 Farmer Panel  
- Real-time crop monitoring  
- Submit progress reports  
- View growth stages  
- Automated email notifications  
- Activity alerts  

### 🛠 Admin Panel  
- Add / edit / delete crops  
- Farmer account management  
- Crop stage updates  
- Automated notifications  
- Knowledge base management  

### 📩 Smart Notification Engine  
- SMTP-based email alerts using PHPMailer  
- Secure and event-driven notifications  

---

## 🧠 Machine Learning Integration  

The crop disease detection ML model is deployed separately as a microservice for scalability and performance.

Repository: **crop-disease-detection-ml**

---

## 🛠 Tech Stack  

### Backend  
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![PHPMailer](https://img.shields.io/badge/PHPMailer-ff69b4?style=for-the-badge)

### Database  
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

### Frontend  
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

### Tools & Deployment  
![XAMPP](https://img.shields.io/badge/XAMPP-ff8c00?style=for-the-badge)
![InfinityFree](https://img.shields.io/badge/InfinityFree-00bfff?style=for-the-badge)

---

## 📁 Project Structure  


smart-cultivation-system/
├── PHPMailer-master/
│   └── ... (PHPMailer library files)
├── add_crop.php
├── admin_add_crop.php
├── admin_dashboard.php
├── admin_delete_crop.php
├── admin_update_stage.php
├── crop_management.php
├── db_connection.php
├── delete_crop.php
├── delete_farmer.php
├── edit_crop.php
├── edit_farmer.php
├── farmer_dashboard.php
├── farmer_reports.php
├── farmers_admin.php
├── fetch_notifications.php
├── index.php
├── knowledge_base.php
├── knowledge_base_admin.php
├── languages/
├── login.php
├── logout.php
├── mark_notification.php
├── notifications_admin.php
├── register.php
├── smart_cultivation.sql
├── toggle_farmer.php
├── update_profile.php
├── update_stage.php
├── .gitmodules
├── README.md
└── ...

---

## ⚙ Installation  

1. Clone the repository:  
```bash
git clone https://github.com/joyswapnilrajparadeshi-cmd/smart-cultivation-system.git

Import smart_cultivation.sql into MySQL.

Update db_connection.php with your database credentials.

Start XAMPP / WAMP / LAMP.

Open in browser: http://localhost/smart-cultivation-system/
