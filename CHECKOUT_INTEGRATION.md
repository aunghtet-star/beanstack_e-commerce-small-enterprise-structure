# BeanStack E-Commerce - Checkout Integration

## SOLID Principles Implementation

This checkout system follows SOLID principles through service-based architecture:

### Single Responsibility Principle (SRP)
Each service class has a single, well-defined responsibility:

- **CartService**: Manages shopping cart session operations
- **OrderService**: Handles order creation, totals calculation, and status updates
- **PaymentService**: Processes payments via Stripe and manages payment method defaults
- **CheckoutController**: Coordinates checkout flow (thin controller pattern)
- **StripeWebhookController**: Handles Stripe webhook events

### Open/Closed Principle (OCP)
Services are open for extension but closed for modification:
- New payment providers can be added without modifying existing code
- Webhook handlers use match expressions for easy extension
- Order status transitions can be extended via OrderService methods

### Liskov Substitution Principle (LSP)
Service interfaces can be swapped with implementations:
- Services are injected via constructor dependency injection
- Controllers depend on service contracts, not concrete implementations

### Interface Segregation Principle (ISP)
Each service provides focused, cohesive methods:
- CartService: session management, cart clearing
- OrderService: order lifecycle operations
- PaymentService: payment processing and status updates

### Dependency Inversion Principle (DIP)
High-level modules depend on abstractions:
- Controllers receive services via constructor injection
- Laravel's service container manages dependencies
- Services are type-hinted for automatic resolution

## Architecture Overview

```
┌─────────────────────────────────────┐
│     CheckoutController              │
│  (Coordinates checkout flow)        │
└──────────┬──────────┬───────────────┘
           │          │
    ┌──────▼────┐  ┌─▼──────────────┐
    │CartService│  │  OrderService  │
    └───────────┘  └────────┬────────┘
                            │
                   ┌────────▼────────┐
                   │ PaymentService  │
                   └─────────────────┘
```

## Service Responsibilities

### CartService
- `getSessionId()`: Retrieve or create cart session
- `clearCart(string $sessionId)`: Empty cart after successful checkout

### OrderService
- `createOrderFromCart(string $sessionId, string $email)`: Create order from cart items
- `calculateTotals($cartItems)`: Compute subtotal, tax, shipping, total
- `markOrderPaid(Order $order)`: Update order to paid status
- `markOrderCancelled(Order $order)`: Cancel order on payment failure

### PaymentService
- `chargeCustomer(User, int $amount, string $method, Order)`: Process Stripe payment
- `savePaymentMethodAsDefault(User, string $method)`: Set default payment method
- `markPaymentCaptured/Failed/Refunded(string $providerId)`: Update payment status

## Setup Instructions

### 1. Install Dependencies
```bash
./vendor/bin/sail composer require laravel/cashier
./vendor/bin/sail npm install
```

### 2. Publish and Run Migrations
```bash
./vendor/bin/sail artisan vendor:publish --tag="cashier-migrations"
./vendor/bin/sail artisan migrate
```

### 3. Configure Stripe Keys
Add to `.env`:
```env
STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
```

### 4. Build Frontend Assets
```bash
./vendor/bin/sail npm run build
# or for development
./vendor/bin/sail npm run dev
```

### 5. Configure Stripe Webhooks (Production)
Point Stripe webhook to:
```
POST https://yourdomain.com/stripe/webhook
```

Events to subscribe:
- `payment_intent.succeeded`
- `payment_intent.payment_failed`
- `charge.refunded`

## Features

### Checkout Flow
1. **Cart Review**: Display items, subtotal, tax, shipping
2. **Payment Entry**: Stripe Elements card input
3. **Save Card Option**: Checkbox to save payment method as default
4. **Order Creation**: Atomic transaction creates order, reserves stock
5. **Payment Processing**: Charge via Laravel Cashier
6. **Stock Management**: Decrement inventory, record stock movements
7. **Cart Clearing**: Empty cart on success
8. **Success Page**: Display confirmation with toast

### Pricing Logic
- **Subtotal**: Sum of (price × quantity) for all items
- **Tax**: 10% of subtotal
- **Shipping**: FREE if subtotal ≥ $100, else $10
- **Total**: Subtotal + Tax + Shipping

### Payment Methods
- **One-time**: Card not saved (via `createPaymentMethod`)
- **Saved**: Card attached to customer (via `confirmCardSetup` + `updateDefaultPaymentMethod`)

### Webhook Handling
- `payment_intent.succeeded` → Mark order paid, payment captured
- `payment_intent.payment_failed` → Cancel order, mark payment failed
- `charge.refunded` → Mark payment refunded

### Strong Customer Authentication (SCA)
- Handled via `IncompletePayment` exception
- Redirects to Cashier's payment confirmation flow
- Returns to success page after 3DS completion

## Testing

### Test Cards (Stripe Test Mode)
- **Success**: 4242 4242 4242 4242
- **Requires 3DS**: 4000 0027 6000 3184
- **Declined**: 4000 0000 0000 0002

### Manual Testing Flow
1. Add products to cart
2. Navigate to `/checkout`
3. Enter test card details
4. Toggle "Save card" checkbox (optional)
5. Submit payment
6. Verify order in database:
   ```sql
   SELECT * FROM orders;
   SELECT * FROM order_items;
   SELECT * FROM payments;
   SELECT * FROM stock_movements;
   ```

## Error Handling

- **Empty Cart**: Returns validation error
- **Out of Stock**: Transaction rolls back, shows error
- **Payment Failed**: Redirects with error message
- **Incomplete Payment (3DS)**: Redirects to Cashier payment page

## Database Schema

### Orders
- `id` (ULID), `number`, `customer_email`, `total` (cents), `status`, `placed_at`

### Order Items
- `order_id`, `product_id`, `name_snapshot`, `price_snapshot` (cents), `quantity`

### Payments
- `id` (ULID), `order_id`, `provider`, `provider_id`, `amount` (cents), `status`

### Stock Movements
- `product_id`, `type` (sale/restock/etc.), `quantity`, `description`

## Future Enhancements

- [ ] Order history page for customers
- [ ] Email receipts via Laravel notifications
- [ ] Refund processing UI for admins
- [ ] Multiple payment methods (PayPal, Apple Pay)
- [ ] Subscription products via Cashier
- [ ] Webhook signature verification
- [ ] Address collection and validation
- [ ] Shipping carrier integration
- [ ] Inventory low-stock alerts

## Security Notes

- All payment processing via Stripe (PCI compliant)
- No card details stored locally
- Session-based cart (no auth required for browsing)
- Webhook endpoint publicly accessible (add signature verification for production)
- Stock validated with database locks to prevent overselling

## License
MIT
