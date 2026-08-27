class ExtraModel {
  final String name;
  final double price;
  bool isSelected;

  ExtraModel({
    required this.name,
    required this.price,
    this.isSelected = false,
  });

  factory ExtraModel.fromJson(Map<String, dynamic> json) {
    return ExtraModel(
      name: json['name'],
      price: double.tryParse(json['price'].toString()) ?? 0.0,
      isSelected: json['is_selected'] ?? false,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'price': price,
      'is_selected': isSelected,
    };
  }
}
