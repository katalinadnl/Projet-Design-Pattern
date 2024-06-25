
# PHP Library for Online Payment Interfaces

## Overview

This project is a PHP library designed to provide a unified interface for interacting with various online payment gateways such as Stripe and PayPal. The library is built following SOLID principles and design patterns to ensure it is robust, extensible, and maintainable.

## Features

- **Unified Interface**: Interact with different payment gateways through a common interface.
- **Dynamic Payment Gateway Selection**: Easily switch between different payment gateways.
- **Transaction Management**: Create, execute, and cancel payment transactions.
- **Observer Pattern**: Notify third-party services about the status of payment transactions.

## Installation

### Prerequisites

- PHP 7.4 or higher
- Composer

### Steps

1. **Clone the Repository**

   ```bash
   git clone https://github.com/katalinadnl/Projet-Design-Pattern.git
   cd Projet-Design-Pattern
   ```

2. **Install Dependencies**

   ```bash
   composer install
   ```

## Usage


## Configuration

### Stripe Configuration

- `api_key`: Your Stripe API key.

### PayPal Configuration

- `client_id`: Your PayPal client ID.
- `client_secret`: Your PayPal client secret.

You can also configure additional settings such as redirect URLs for PayPal transactions by passing them to the `initialize` method or using setter methods.

## Extending the Library

### Adding a New Payment Gateway

1. **Create a new class that implements `PaymentInterface`.**
2. **Implement all required methods (`initialize`, `createTransaction`, `executeTransaction`, `cancelTransaction`, `getTransactionStatus`).**
3. **Add the new class to `PaymentFactory`.**

### Example: Adding a New Payment Gateway

```php
namespace ProjetDesignPattern\PaymentGateways;

use ProjetDesignPattern\Interfaces\PaymentInterface;

class NewPaymentGateway implements PaymentInterface
{
    // Implement all required methods
}
```

```php
namespace ProjetDesignPattern\Factory;

use ProjetDesignPattern\Interfaces\PaymentInterface;
use ProjetDesignPattern\PaymentGateways\NewPaymentGateway;

class PaymentFactory {
    public static function createPaymentGateway(string $type): PaymentInterface {
        switch ($type) {
            case 'newgateway':
                return new NewPaymentGateway();
            // other cases...
            default:
                throw new \Exception("Unsupported payment gateway");
        }
    }
}
```

## Contributing

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes.
4. Commit your changes (`git commit -m 'Add some feature'`).
5. Push to the branch (`git push origin feature-branch`).
6. Open a pull request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact

For any questions or suggestions, feel free to contact us at [contact@katy.com].
