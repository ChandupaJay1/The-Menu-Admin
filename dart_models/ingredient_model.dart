class Ingredient {
  final String name;
  final String iconUrl;

  Ingredient({
    required this.name,
    required this.iconUrl,
  });

  factory Ingredient.fromJson(Map<String, dynamic> json) {
    return Ingredient(
      name: json['name'],
      iconUrl: json['icon_url'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'icon_url': iconUrl,
    };
  }
}
