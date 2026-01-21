import 'package:flutter/material.dart';
import 'package:summa_move/models/performances.dart';
import '../models/exercise.dart';
import '../services/api_service.dart';

class PerformanceFormScreen extends StatefulWidget {
  final Performance? performance;

  const PerformanceFormScreen({super.key, this.performance});

  @override
  State<PerformanceFormScreen> createState() => _PerformanceFormScreenState();
}

class _PerformanceFormScreenState extends State<PerformanceFormScreen> {
  final _formKey = GlobalKey<FormState>();

  final _repsController = TextEditingController();
  final _setsController = TextEditingController();
  final _weightController = TextEditingController();
  final _notesController = TextEditingController();

  List<Exercise> _exercises = [];
  String? _selectedExerciseId;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadExercises();
  }

  void _loadExercises() async {
    final exercises = await ApiService.getExercises();
    setState(() {
      _exercises = exercises;
      _isLoading = false;

      // FILL DATA IF EDITING
      if (widget.performance != null) {
        _selectedExerciseId = widget.performance!.exerciseId;
        _repsController.text = widget.performance!.reps.toString();
        _setsController.text = widget.performance!.sets.toString();
        _weightController.text = widget.performance!.weight != null
            ? widget.performance!.weight.toString()
            : '';
        _notesController.text = widget.performance!.notes ?? '';
      }
    });
  }

  void _save() async {
    if (_formKey.currentState!.validate() && _selectedExerciseId != null) {
      double? weightValue;
      if (_weightController.text.isNotEmpty) {
        weightValue = double.tryParse(_weightController.text);
      }

      final p = Performance(
        id: widget.performance?.id,
        exerciseId: _selectedExerciseId!,
        reps: int.parse(_repsController.text),
        sets: int.parse(_setsController.text),
        weight: weightValue,
        notes: _notesController.text,
      );

      bool success;
      setState(() => _isLoading = true); // Show loading spinner while saving

      if (widget.performance == null) {
        // CREATE NEW
        success = await ApiService.createPerformance(p);
      } else {
        // UPDATE EXISTING (NEW CODE)
        success = await ApiService.updatePerformance(p);
      }

      if (mounted) {
        setState(() => _isLoading = false);
        if (success) {
          Navigator.pop(context); // Go back to dashboard
        } else {
          ScaffoldMessenger.of(
            context,
          ).showSnackBar(const SnackBar(content: Text("Fout bij opslaan")));
        }
      }
    } else if (_selectedExerciseId == null) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(const SnackBar(content: Text("Selecteer een oefening")));
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading)
      return const Scaffold(body: Center(child: CircularProgressIndicator()));

    return Scaffold(
      appBar: AppBar(
        title: Text(
          widget.performance == null ? "Nieuwe Prestatie" : "Wijzig Prestatie",
        ),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: SingleChildScrollView(
            child: Column(
              children: [
                DropdownButtonFormField<String>(
                  value: _selectedExerciseId,
                  decoration: const InputDecoration(
                    labelText: "Kies Oefening",
                    border: OutlineInputBorder(),
                  ),
                  items: _exercises.map((e) {
                    return DropdownMenuItem(value: e.id, child: Text(e.name));
                  }).toList(),
                  onChanged: (val) => setState(() => _selectedExerciseId = val),
                ),
                const SizedBox(height: 10),
                Row(
                  children: [
                    Expanded(
                      child: TextFormField(
                        controller: _setsController,
                        decoration: const InputDecoration(
                          labelText: "Sets",
                          border: OutlineInputBorder(),
                        ),
                        keyboardType: TextInputType.number,
                        validator: (v) => v!.isEmpty ? "*" : null,
                      ),
                    ),
                    const SizedBox(width: 10),
                    Expanded(
                      child: TextFormField(
                        controller: _repsController,
                        decoration: const InputDecoration(
                          labelText: "Reps",
                          border: OutlineInputBorder(),
                        ),
                        keyboardType: TextInputType.number,
                        validator: (v) => v!.isEmpty ? "*" : null,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 10),
                TextFormField(
                  controller: _weightController,
                  decoration: const InputDecoration(
                    labelText: "Gewicht (kg)",
                    border: OutlineInputBorder(),
                  ),
                  keyboardType: TextInputType.numberWithOptions(decimal: true),
                ),
                const SizedBox(height: 10),
                TextFormField(
                  controller: _notesController,
                  decoration: const InputDecoration(
                    labelText: "Notities",
                    border: OutlineInputBorder(),
                  ),
                  maxLines: 2,
                ),
                const SizedBox(height: 20),
                ElevatedButton(
                  onPressed: _save,
                  style: ElevatedButton.styleFrom(
                    minimumSize: const Size.fromHeight(50),
                  ),
                  child: Text(
                    widget.performance == null ? "Toevoegen" : "Opslaan",
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
