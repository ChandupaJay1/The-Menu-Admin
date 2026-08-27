import 'ingredient_model.dart';

class FoodModel {
  final int id;
  final String name;
  final String description;
  final double price;
  final String imageUrl;
  final String type; // Breakfast, Lunch, Dinner
  final List<String> availableDays; // ["Mon", "Tue", ...]
  final String? prepTime;
  final int? calories;
  final String? difficulty;
  final String? servings;
  final double rating;
  final List<Ingredient> ingredients;

  FoodModel({
    required this.id,
    required this.name,
    required this.description,
    required this.price,
    required this.imageUrl,
    required this.type,
    required this.availableDays,
    this.prepTime,
    this.calories,
    this.difficulty,
    this.servings,
    this.rating = 0.0,
    this.ingredients = const [],
  });

  factory FoodModel.fromJson(Map<String, dynamic> json) {
    return FoodModel(
      id: json['id'],
      name: json['name'],
      description: json['description'] ?? '',
      price: double.tryParse(json['price'].toString()) ?? 0.0,
      imageUrl: json['image_url'],
      type: json['type'],
      availableDays: List<String>.from(json['available_days'] ?? []),
      prepTime: json['prep_time'],
      calories: json['calories'],
      difficulty: json['difficulty'],
      servings: json['servings'],
      rating: double.tryParse(json['rating'].toString()) ?? 0.0,
      ingredients: (json['ingredients'] as List? ?? [])
          .map((i) => Ingredient.fromJson(i))
          .toList(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'description': description,
      'price': price,
      'image_url': imageUrl,
      'type': type,
      'available_days': availableDays,
      'prep_time': prepTime,
      'calories': calories,
      'difficulty': difficulty,
      'servings': servings,
      'rating': rating,
      'ingredients': ingredients.map((i) => i.toJson()).toList(),
    };
  }
}
