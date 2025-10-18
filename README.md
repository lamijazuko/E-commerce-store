# EverCart E-Commerce Store

A modern, responsive single-page e-commerce application built with HTML, CSS, JavaScript, and Bootstrap.

## 🚀 Features

- **Single Page Application (SPA)**: Seamless navigation without page reloads
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile devices
- **Modern UI/UX**: Clean, professional design with smooth animations
- **Shopping Cart**: Add/remove items, quantity management, and cart persistence
- **User Authentication**: Login and registration pages with form validation
- **Product Management**: Browse products by category with search functionality
- **Order History**: Track past orders and order status
- **User Profile**: Manage account information and preferences

## 📁 Project Structure

```
evercart_e_commerce_store/
├── index.html                 # Main HTML file
├── README.md                 # Project documentation
├── database_schema.md        # Database schema and ERD
├── backend/                  # Backend directory
│   ├── routes/              # API routes
│   ├── services/            # Business logic
│   └── dao/                 # Data access objects
└── frontend/                # Frontend directory
    ├── css/
    │   └── main.css         # Main stylesheet
    ├── js/
    │   ├── main.js          # SPA navigation and core functionality
    │   └── app.js           # Page content and application logic
    ├── views/               # HTML templates (for future use)
    └── static/              # Static assets (images, etc.)
```

## 🛠️ Technologies Used

- **HTML5**: Semantic markup and structure
- **CSS3**: Modern styling with CSS Grid and Flexbox
- **JavaScript (ES6+)**: SPA functionality and dynamic content
- **Bootstrap 5**: Responsive framework and components
- **Font Awesome**: Icons and visual elements
- **Local Storage**: Client-side data persistence

## 📱 Pages Included

1. **Home Page**: Hero section, featured products, and categories
2. **Products Page**: Product grid with search and filter functionality
3. **Product Detail Page**: Detailed product view with add to cart
4. **Categories Page**: Browse products by category
5. **Shopping Cart**: Cart management and checkout process
6. **Login Page**: User authentication
7. **Register Page**: New user registration
8. **Profile Page**: User account management
9. **Orders Page**: Order history and tracking
10. **About Page**: Company information and story

## 🎨 Design Features

- **Consistent Theme**: Primary blue color scheme with professional styling
- **Smooth Animations**: Fade-in effects and hover animations
- **Mobile-First**: Responsive design that works on all devices
- **Accessibility**: Semantic HTML and ARIA labels
- **Modern Typography**: Clean, readable fonts
- **Visual Hierarchy**: Clear information architecture

## 💾 Database Schema

The application is designed to work with a relational database containing 6 main entities:

1. **Users**: Customer account information
2. **Categories**: Product categorization (with hierarchical support)
3. **Products**: Product catalog and inventory
4. **Orders**: Customer orders and transactions
5. **Order_Items**: Individual items within orders
6. **Reviews**: Product reviews and ratings

See `database_schema.md` for detailed ERD and relationship descriptions.

## 🚀 Getting Started

1. **Clone the repository**:
   ```bash
   git clone [repository-url]
   cd lamija_e_commerce_store
   ```

2. **Open in browser**:
   - Simply open `index.html` in a web browser
   - Or use a local server for better development experience:
   ```bash
   # Using Python
   python -m http.server 8000
   
   # Using Node.js
   npx serve .
   ```

3. **Access the application**:
   - Navigate to `http://localhost:8000` (if using local server)
   - Or open `index.html` directly in browser

## 🔧 Functionality

### Shopping Cart
- Add products to cart with quantity selection
- Update quantities or remove items
- Persistent cart using localStorage
- Cart total calculation with tax and shipping

### User Authentication
- Login with email and password
- Registration with form validation
- User session management
- Protected routes for authenticated users

### Product Browsing
- Search products by name
- Filter by category
- Product detail views with ratings
- Responsive product grid

### Order Management
- View order history
- Track order status
- Order details with itemized breakdown

## 🎯 Future Enhancements

- Backend API integration
- Real payment processing
- Advanced search and filtering
- Product wishlist functionality
- Email notifications
- Admin dashboard
- Inventory management
- Customer support chat

## 📄 License

This project is created for educational purposes as part of an e-commerce development assignment.

## 👥 Contributing

This is a project assignment, but suggestions for improvements are welcome!

---

**Note**: This is a frontend-only implementation for demonstration purposes. In a production environment, you would need to implement backend services, database connections, and security measures.
