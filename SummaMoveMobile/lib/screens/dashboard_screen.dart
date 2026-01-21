import 'package:flutter/material.dart';
import 'package:summa_move/models/performances.dart';
import '../services/api_service.dart';
import '../widgets/main_drawer.dart';
import 'performance_form_screen.dart';

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  late Future<List<Performance>> _performances;

  @override
  void initState() {
    super.initState();
    _refreshList();
  }

  void _refreshList() {
    setState(() {
      _performances = ApiService.getPerformances();
    });
  }

  void _deleteItem(String id) async {
    await ApiService.deletePerformance(id);
    _refreshList();
    if (mounted) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(const SnackBar(content: Text("Verwijderd!")));
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Mijn Resultaten"),
        backgroundColor: Colors.blue.shade100,
      ),
      drawer: const MainDrawer(),
      body: FutureBuilder<List<Performance>>(
        future: _performances,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting)
            return const Center(child: CircularProgressIndicator());
          if (snapshot.hasError)
            return Center(child: Text("Fout: ${snapshot.error}"));

          final data = snapshot.data ?? [];
          if (data.isEmpty)
            return const Center(child: Text("Nog geen prestaties toegevoegd."));

          return ListView.builder(
            itemCount: data.length,
            itemBuilder: (context, index) {
              final p = data[index];
              return Card(
                margin: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                child: ListTile(
                  leading: CircleAvatar(
                    backgroundColor: Colors.blue,
                    child: Text(
                      "${p.weight?.toInt() ?? 0}",
                      style: const TextStyle(color: Colors.white, fontSize: 12),
                    ),
                  ),
                  title: Text("Sets: ${p.sets} x Reps: ${p.reps}"),
                  subtitle: Text(p.notes ?? "Geen notities"),

                  // UPDATED TRAILING SECTION
                  trailing: Row(
                    mainAxisSize: MainAxisSize.min, // Keep it compact
                    children: [
                      // EDIT BUTTON
                      IconButton(
                        icon: const Icon(Icons.edit, color: Colors.blue),
                        onPressed: () async {
                          // Navigate to form with the existing performance data
                          await Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) =>
                                  PerformanceFormScreen(performance: p),
                            ),
                          );
                          _refreshList(); // Reload list when we come back
                        },
                      ),
                      // DELETE BUTTON
                      IconButton(
                        icon: const Icon(Icons.delete, color: Colors.red),
                        onPressed: () => _deleteItem(p.id!),
                      ),
                    ],
                  ),
                ),
              );
            },
          );
        },
      ),
      floatingActionButton: FloatingActionButton(
        child: const Icon(Icons.add),
        onPressed: () async {
          await Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => const PerformanceFormScreen(),
            ),
          );
          _refreshList();
        },
      ),
    );
  }
}
