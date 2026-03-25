import 'food_model.dart';

class EventModel {
  final int? id;
  final String name;
  final DateTime startDate;
  final DateTime endDate;
  final Map<String, List<EventItem>> dailyMenus; // Key is date string YYYY-MM-DD
  final double totalCost;

  EventModel({
    this.id,
    required this.name,
    required this.startDate,
    required this.endDate,
    required this.dailyMenus,
    required this.totalCost,
  });

  factory EventModel.fromJson(Map<String, dynamic> json) {
    var dailyMenusJson = json['daily_menus'] as Map<String, dynamic>;
    Map<String, List<EventItem>> menus = {};
    
    dailyMenusJson.forEach((key, value) {
      menus[key] = (value as List).map((item) => EventItem.fromJson(item)).toList();
    });

    return EventModel(
      id: json['id'],
      name: json['name'],
      startDate: DateTime.parse(json['start_date']),
      endDate: DateTime.parse(json['end_date']),
      dailyMenus: menus,
      totalCost: double.tryParse(json['total_cost'].toString()) ?? 0.0,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'start_date': startDate.toIso8601String().split('T')[0],
      'end_date': endDate.toIso8601String().split('T')[0],
      'daily_menus': dailyMenus.map((key, value) => MapEntry(key, value.map((v) => v.toJson()).toList())),
      'total_cost': totalCost,
    };
  }
}

class EventItem {
  final FoodModel food;
  final String mealType; // Breakfast, Lunch, Dinner
  final int quantity;

  EventItem({
    required this.food,
    required this.mealType,
    required this.quantity,
  });

  factory EventItem.fromJson(Map<String, dynamic> json) {
    return EventItem(
      food: FoodModel.fromJson(json['food']),
      mealType: json['meal_type'],
      quantity: json['quantity'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'food_id': food.id,
      'meal_type': mealType,
      'quantity': quantity,
    };
  }
}
