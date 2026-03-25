import 'food_model.dart';

class CartItem {
  final FoodModel food;
  int quantity;

  CartItem({
    required this.food,
    this.quantity = 1,
  });

  double get total => food.price * quantity;

  factory CartItem.fromJson(Map<String, dynamic> json) {
    return CartItem(
      food: FoodModel.fromJson(json['food']),
      quantity: json['quantity'] ?? 1,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'food_id': food.id,
      'quantity': quantity,
    };
  }
}

class CartModel {
  final List<CartItem> items;
  final String deliveryAddress;
  final double deliveryFee;

  CartModel({
    required this.items,
    required this.deliveryAddress,
    this.deliveryFee = 0.0,
  });

  double get subtotal => items.fold(0, (sum, item) => sum + item.total);
  double get grandTotal => subtotal + deliveryFee;

  Map<String, dynamic> toJson() {
    return {
      'items': items.map((i) => i.toJson()).toList(),
      'delivery_address': deliveryAddress,
      'delivery_fee': deliveryFee,
      'total_price': grandTotal,
    };
  }
}
