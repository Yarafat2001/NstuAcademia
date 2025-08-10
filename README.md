✨ Overview
NstuAcademia is a web application designed to streamline academic operations at NSTU.  
It enables course registration, seat allocation, admit card generation, and dashboard management for both students and faculty.

🚀 Features

✅ Student & Teacher Dashboards  
✅ Course Registration & Seat Management  
✅ PDF Admit Card Generation (via **FPDF**)  
✅ Email Notifications (**PHPMailer**)  
✅ Secure Authentication (OAuth & Password)  
✅ Responsive Web Interface  

🛠️ Tech Stack

| Technology      | Purpose                       |
|-----------------|--------------------------------|
| PHP         | Backend logic                 |
| HTML/CSS    | Frontend UI                   |
| MySQL       | Database                      |
| FPDF        | PDF generation                |
| PHPMailer   | Email sending                  |
| JavaScript  | Interactivity                  |

📂 Project Structure

NstuAcademia/
│
├── assets/ # Images, CSS, JS files
├── config/ # Database configuration
├── includes/ # Reusable components
├── pages/ # Main application pages
├── vendor/ # Composer dependencies
├── nstu_academia.sql # Database schema
└── index.php # Entry point

yaml
Copy
Edit

 ⚙️ Installation & Setup

1️⃣ Clone the Repository**  
bash
git clone https://github.com/Yarafat2001/NstuAcademia.git
cd NstuAcademia

2️⃣ Install Dependencies

bash
Copy
Edit
composer install
3️⃣ Setup Database

Create a MySQL database

Import nstu_academia.sql into it

Update config.php with DB credentials

4️⃣ Run the Application

bash
Copy
Edit
php -S localhost:8000
Open in browser → http://localhost:8000

📸 Screenshots
Student Dashboard	Admit Card

🤝 Contributing
Fork the repo

Create a new branch (feature/YourFeature)

Commit changes

Push and create a Pull Request

📜 License
This project is licensed under the MIT License — feel free to use and modify.

📬 Contact
Yeasin Arafat


