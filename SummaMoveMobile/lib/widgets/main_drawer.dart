import 'package:flutter/material.dart';
import '../screens/dashboard_screen.dart';
import '../screens/exercise_list_screen.dart'; // Import the new screen
import '../screens/about_screen.dart';

class MainDrawer extends StatelessWidget {
  const MainDrawer({super.key});

  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: ListView(
        padding: EdgeInsets.zero,
        children: [
          const DrawerHeader(
            decoration: BoxDecoration(color: Colors.blue),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                Icon(Icons.person, color: Colors.white, size: 40),
                SizedBox(height: 10),
                Text(
                  "Summa Move",
                  style: TextStyle(color: Colors.white, fontSize: 24),
                ),
              ],
            ),
          ),
          // Link to Dashboard
          ListTile(
            leading: const Icon(Icons.list_alt),
            title: const Text("Mijn Resultaten"),
            onTap: () {
              Navigator.pop(context);
              Navigator.pushReplacement(
                context,
                MaterialPageRoute(
                  builder: (context) => const DashboardScreen(),
                ),
              );
            },
          ),
          // Link to Exercises (THE NEW ONE)
          ListTile(
            leading: const Icon(Icons.fitness_center),
            title: const Text("Oefeningen Overzicht"),
            onTap: () {
              Navigator.pop(context);
              Navigator.pushReplacement(
                context,
                MaterialPageRoute(
                  builder: (context) => const ExerciseListScreen(),
                ),
              );
            },
          ),
          const Divider(),
          // Link to About
          ListTile(
            leading: const Icon(Icons.info),
            title: const Text("Over de app"),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => const AboutScreen()),
              );
            },
          ),
        ],
      ),
    );
  }
}
