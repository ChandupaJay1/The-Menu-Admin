import 'food_model.dart';
import 'extra_model.dart';

class PlannedMeal {
  final int? id;
  final FoodModel food;
  final List<ExtraModel> selectedExtras;
  final String mealType; // Breakfast, Lunch, Dinner
  final DateTime date;
  final double totalPrice;

  PlannedMeal({
    this.id,
    required this.food,
    this.selectedExtras = const [],
    required this.mealType,
    required this.date,
    required this.totalPrice,
  });

  factory PlannedMeal.fromJson(Map<String, dynamic> json) {
    return PlannedMeal(
      id: json['id'],
      food: FoodModel.fromJson(json['food']),
      selectedExtras: (json['selected_extras'] as List? ?? [])
          .map((e) => ExtraModel.fromJson(e))
          .toList(),
      mealType: json['meal_type'],
      date: DateTime.parse(json['date']),
      totalPrice: double.tryParse(json['total_price'].toString()) ?? 0.0,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'food_id': food.id, // Usually send ID to Laravel
      'selected_extras': selectedExtras.map((e) => e.toJson()).toList(),
      'meal_type': mealType,
      'date': date.toIso8601String().split('T')[0], // YYYY-MM-DD
      'total_price': totalPrice,
    };
  }
}
