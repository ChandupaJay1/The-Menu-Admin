import 'cart_model.dart';

class OrderModel {
  final int? id;
  final List<CartItem> items;
  final double totalPrice;
  final String status; // pending, processing, delivered, cancelled
  final String address;
  final String? paymentMethod;
  final DateTime createdAt;

  OrderModel({
    this.id,
    required this.items,
    required this.totalPrice,
    required this.status,
    required this.address,
    this.paymentMethod,
    required this.createdAt,
  });

  factory OrderModel.fromJson(Map<String, dynamic> json) {
    return OrderModel(
      id: json['id'],
      items: (json['items'] as List).map((i) => CartItem.fromJson(i)).toList(),
      totalPrice: double.tryParse(json['total_price'].toString()) ?? 0.0,
      status: json['status'],
      address: json['address'],
      paymentMethod: json['payment_method'],
      createdAt: DateTime.parse(json['created_at']),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'items': items.map((i) => i.toJson()).toList(),
      'total_price': totalPrice,
      'status': status,
      'address': address,
      'payment_method': paymentMethod,
    };
  }
}
