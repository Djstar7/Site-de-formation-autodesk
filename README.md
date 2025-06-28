# 🏗️ Civil Design — Plans de Maison & Formations Autodesk

Bienvenue sur **Civil Design**, une plateforme web moderne qui combine la **vente de plans architecturaux** avec des **formations pratiques** sur des logiciels de conception comme **AutoCAD** et **ArchiCAD**.

Pensé pour les passionnés du BTP, les étudiants, les architectes et les curieux, ce site a pour objectif de **démocratiser l’accès à l’architecture moderne au Cameroun et en Afrique**. 🌍

---

## 🚀 Fonctionnalités clés

- 📦 Catalogue interactif de **plans de maison**
- 🎓 Accès à des **formations certifiantes** (AutoCAD, ArchiCAD…)
- 🛒 Système de **commande et panier**
- 👥 Gestion des rôles : **Visiteur**, **Client**, **Formateur**, **Admin**
- 🧾 Facturation et historique des achats
- 📱 Interface **responsive & rapide** avec Tailwind CSS
- 💳 Paiement mobile à intégrer (MTN, Orange, NotchPay, CinetPay)

---

## 🧰 Stack technique

| Côté | Techno utilisée |
|------|------------------|
| Frontend | Vue.js 3 + Tailwind CSS |
| Backend | Laravel 12 |
| Base de données | MySQL |
| Bundler | Vite |
| Authentification | Laravel Sanctum |

---

## 📁 Arborescence du projet

/resources
└── /js
└── components/
└── views/
└── /css (via Tailwind)

/routes
└── web.php

/app
└── Models/
└── Http/Controllers/

/database
└── migrations/
└── seeders/

.env
vite.config.js
package.json



---

## 🛠️ Installation en local

1. Cloner le dépôt :
   ```bash
   git clone https://github.com/Djstar7/Site-de-formation-autodesk.git
   cd Site-de-formation-autodesk
2. Installe les dependeces :
   
       composer install
       npm install
4. Configurer l'environnement:

       cp .env.example .env
       php artisan key:generate

5.  Visiter :

           http://localhost:8000




👨‍💻 Auteur

Développé par Stael DJUNE
📍 Mbouda, Cameroun
📧 infodjstar7@gmail.com
🔗 https://www.linkedin.com/in/dj-star-info-b34669357/
🤝 Contribution




   
   

