# Multi-Restaurant Food Ordering Application  

Welcome to the Multi-Restaurant Food Ordering Application! This project was developed as part of the course "Laravel 11 Build Multi Restaurant Food Order Application A-Z" and enhanced with additional features to provide a comprehensive system for managing multiple restaurants, their orders, and customer interactions.

## Features  

### Core Features  
- **Multi-Restaurant Support**: Manage multiple restaurants within a single system.  
- **User Roles and Permissions**: Role-based access control to secure and streamline operations.  
- **Account Verification**: Email-based account verification for secure user registration.  
- **Password Reset**: Secure password recovery system via email.  
- **Electronic Payment Integration**: Seamless integration with Stripe for online payments.  
- **Cash on Delivery Option**: An alternative payment method for users.  

### Additional Functionalities  
- **Order Management**: Efficient management of customer orders.  
- **Restaurant Dashboard**: A dedicated dashboard for restaurant owners to manage their menus and orders.  
- **Responsive Design**: Fully responsive UI/UX for a smooth experience across devices.  
- **Custom Authentication**: Implemented custom authentication logic for enhanced security.  
- **Dynamic Menu Management**: Easily add, edit, or remove menu items.  

## Technology Stack  
- **Backend**: Laravel 11  
- **Frontend**: Blade Templates, HTML, CSS, JavaScript  
- **Database**: MySQL  
- **Payment Gateway**: Stripe  
- **Others**: Composer, NPM  

## Setup Instructions  

### Prerequisites  
- PHP 8.2 or higher  
- Composer  
- Node.js and NPM  
- MySQL  

### Installation Steps  
1. Clone the repository:  
   ```bash  
   git clone https://github.com/your-username/multi-restaurant-app.git  
   cd multi-restaurant-app  
   ```  

2. Install dependencies:  
   ```bash  
   composer install  
   npm install && npm run dev  
   ```  

3. Configure the environment file:  
   - Copy `.env.example` to `.env`.  
   - Set your database and Stripe credentials.  

4. Run migrations and seeders:  
   ```bash  
   php artisan migrate --seed  
   ```  

5. Start the development server:  
   ```bash  
   php artisan serve  
   ```  

### Testing  
- Run unit tests with:  
  ```bash  
  php artisan test  
  ```  

## How to Use  
- Register as a user to place orders or as a restaurant owner to manage a restaurant.  
- Add menu items, manage orders, and track payments through the dashboard.  
- Use Stripe for secure online payments or opt for cash on delivery.  

## Video Demo  
Check out the full demo of the application [here](#).  

## License  
This project is open-source and available under the [MIT License](LICENSE).  

## Contact  
If you have any questions or suggestions, feel free to reach out:  
- Email: [mohamedahmeddev333@gmail.com](mailto:mohamedahmeddev333@gmail.com)  
- LinkedIn: [LinkedIn Profile](https://www.linkedin.com/in/mohamed-ahmed-354a572a3)  
