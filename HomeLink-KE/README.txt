HomeLink-KE — XAMPP Full-Stack PHP Build
========================================

1. Copy the folder named HomeLink-KE into:
   C:\xampp\htdocs\

2. Start Apache and MySQL in XAMPP.

3. Open:
   http://localhost/HomeLink-KE/setup-db.php

   This creates/migrates the homelink_ke database and preserves existing HomeLink data.

4. Open the website:
   http://localhost/HomeLink-KE/

5. Admin login is intentionally hidden from the main navigation. It is in the website footer:
   Admin

   Direct path:
   http://localhost/HomeLink-KE/admin/login.php

DEFAULT ADMIN
-------------
Username: admin
Password: admin123

Change the password after the first login from Admin > My password.

SUPER ADMIN
-----------
The default admin is a super administrator. Super admins can create/disable other admin accounts and reset their passwords.

IMPORTANT BUSINESS WORKFLOW
---------------------------
- Public visitors do not need accounts.
- Landlords submit their property details to HomeLink-KE.
- HomeLink-KE verifies and publishes listings.
- Landlord private contact details are never shown publicly.
- Clients contact HomeLink-KE for inquiries and viewings.
- Rent/deposit are intended to be paid directly to the landlord after viewing and agreement.
- HomeLink-KE service fees are managed in Admin > Service fees.
- BnB listings use the same property system and have a separate booking/request workflow.

SECURITY / MAINTENANCE
----------------------
- Admin passwords use password_hash/password_verify.
- Admin actions use CSRF tokens.
- Admin pages require an authenticated session.
- Super-admin functions are restricted by role.
- Uploads are restricted to image types and the uploads folder blocks PHP execution.
- Audit logs record important admin actions.
- The SQL file is blocked from direct web access by .htaccess.

If you later move the project to another folder name, update appBaseUrl() in:
includes/auth.php

DATABASE
--------
Database: homelink_ke
Host: localhost
User: root
Password: (blank by default in XAMPP)
