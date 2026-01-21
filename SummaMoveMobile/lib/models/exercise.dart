class Exercise {
  final String id;
  final String name;
  final String description;
  final String category; // New
  final String difficulty; // New

  Exercise({
    required this.id,
    required this.name,
    required this.description,
    required this.category,
    required this.difficulty,
  });

  factory Exercise.fromJson(Map<String, dynamic> json) {
    return Exercise(
      id: json['id'] ?? '',
      name: json['name'] ?? 'Naamloos',
      description: json['description'] ?? '',
      category: json['category'] ?? 'Algemeen',
      difficulty: json['difficulty'] ?? 'N.v.t.',
    );
  }
}
