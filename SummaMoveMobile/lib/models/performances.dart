class Performance {
  final String? id; // API gebruikt waarschijnlijk String IDs (UUIDs)
  final String exerciseId;
  final int reps;
  final int sets;
  final double? weight;
  final String? notes;
  final String? date;

  Performance({
    this.id,
    required this.exerciseId,
    required this.reps,
    required this.sets,
    this.weight,
    this.notes,
    this.date,
  });

  factory Performance.fromJson(Map<String, dynamic> json) {
    return Performance(
      id: json['id'],
      exerciseId: json['exerciseId'] ?? '',
      reps: json['reps'] ?? 0,
      sets: json['sets'] ?? 0,
      // Veilig omzetten naar double
      weight: (json['weight'] != null)
          ? (json['weight'] as num).toDouble()
          : null,
      notes: json['notes'],
      date: json['date'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'exerciseId': exerciseId,
      'reps': reps,
      'sets': sets,
      'weight': weight,
      'notes': notes,
    };
  }
}
