import 'package:flutter/material.dart';
import '../models/exercise.dart';
import '../services/api_service.dart';
import '../widgets/main_drawer.dart';

class ExerciseListScreen extends StatefulWidget {
  const ExerciseListScreen({super.key});

  @override
  State<ExerciseListScreen> createState() => _ExerciseListScreenState();
}

class _ExerciseListScreenState extends State<ExerciseListScreen> {
  late Future<List<Exercise>> _exercisesFuture;

  @override
  void initState() {
    super.initState();
    _exercisesFuture = ApiService.getExercises();
  }

  // Function to determine color based on difficulty
  Color _getDifficultyColor(String difficulty) {
    switch (difficulty.toLowerCase()) {
      case 'beginner':
        return Colors.green;
      case 'intermediate':
        return Colors.orange;
      case 'expert':
        return Colors.red;
      default:
        return Colors.blue;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Alle Oefeningen"),
        backgroundColor: Colors.blue.shade100,
      ),
      drawer: const MainDrawer(), // Navigation Menu
      body: FutureBuilder<List<Exercise>>(
        future: _exercisesFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return Center(child: Text("Fout: ${snapshot.error}"));
          } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return const Center(child: Text("Geen oefeningen gevonden."));
          }

          final exercises = snapshot.data!;

          return ListView.builder(
            padding: const EdgeInsets.all(8),
            itemCount: exercises.length,
            itemBuilder: (context, index) {
              final ex = exercises[index];
              return Card(
                elevation: 3,
                margin: const EdgeInsets.symmetric(vertical: 8),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Title and Icon
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Expanded(
                            child: Text(
                              ex.name,
                              style: const TextStyle(
                                fontSize: 18,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ),
                          const Icon(Icons.fitness_center, color: Colors.grey),
                        ],
                      ),
                      const SizedBox(height: 8),

                      // Description
                      Text(
                        ex.description,
                        style: TextStyle(color: Colors.grey[700]),
                      ),
                      const SizedBox(height: 12),

                      // Tags (Category & Difficulty)
                      Row(
                        children: [
                          Chip(
                            label: Text(ex.category),
                            backgroundColor: Colors.blue.shade50,
                            labelStyle: const TextStyle(
                              color: Colors.blue,
                              fontSize: 12,
                            ),
                          ),
                          const SizedBox(width: 8),
                          Chip(
                            label: Text(ex.difficulty),
                            backgroundColor: _getDifficultyColor(
                              ex.difficulty,
                            ).withOpacity(0.1),
                            labelStyle: TextStyle(
                              color: _getDifficultyColor(ex.difficulty),
                              fontSize: 12,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              );
            },
          );
        },
      ),
    );
  }
}
