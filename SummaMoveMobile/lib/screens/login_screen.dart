import 'package:flutter/material.dart';
import '../services/auth_service.dart';
import 'dashboard_screen.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  // Toggle between Login and Register view
  bool _isLoginMode = true;
  bool _isLoading = false;

  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();

  void _submit() async {
    setState(() => _isLoading = true);

    bool success;
    if (_isLoginMode) {
      // Login Logic
      success = await AuthService.login(
        _emailController.text,
        _passwordController.text,
      );
    } else {
      // Register Logic
      success = await AuthService.register(
        _nameController.text,
        _emailController.text,
        _passwordController.text,
      );
    }

    setState(() => _isLoading = false);

    if (success && mounted) {
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (context) => const DashboardScreen()),
      );
    } else if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            _isLoginMode
                ? "Ongeldig email of wachtwoord"
                : "Email bestaat al of registratie mislukt",
          ),
        ),
      );
    }
  }

  void _guestLogin() async {
    setState(() => _isLoading = true);
    bool success = await AuthService.loginAsGuest();
    setState(() => _isLoading = false);

    if (success && mounted) {
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (context) => const DashboardScreen()),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: Center(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.fitness_center, size: 80, color: Colors.blue),
              const SizedBox(height: 20),
              Text(
                _isLoginMode ? "Welkom Terug" : "Account Maken",
                style: const TextStyle(
                  fontSize: 26,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 40),

              // NAME FIELD (Only for Register)
              if (!_isLoginMode)
                Column(
                  children: [
                    TextField(
                      controller: _nameController,
                      decoration: const InputDecoration(
                        labelText: 'Volledige Naam',
                        prefixIcon: Icon(Icons.person),
                        border: OutlineInputBorder(),
                      ),
                    ),
                    const SizedBox(height: 16),
                  ],
                ),

              // EMAIL
              TextField(
                controller: _emailController,
                decoration: const InputDecoration(
                  labelText: 'Email',
                  prefixIcon: Icon(Icons.email),
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 16),

              // PASSWORD
              TextField(
                controller: _passwordController,
                obscureText: true,
                decoration: const InputDecoration(
                  labelText: 'Wachtwoord',
                  prefixIcon: Icon(Icons.lock),
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 24),

              // SUBMIT BUTTON
              _isLoading
                  ? const CircularProgressIndicator()
                  : SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: _submit,
                        style: ElevatedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(vertical: 16),
                          textStyle: const TextStyle(fontSize: 18),
                        ),
                        child: Text(_isLoginMode ? "Inloggen" : "Registreren"),
                      ),
                    ),

              const SizedBox(height: 16),

              // TOGGLE LOGIN/REGISTER
              TextButton(
                onPressed: () => setState(() => _isLoginMode = !_isLoginMode),
                child: Text(
                  _isLoginMode
                      ? "Nog geen account? Registreer hier"
                      : "Heb je al een account? Log in",
                ),
              ),

              const Divider(height: 40),

              // GUEST LOGIN BUTTON
              OutlinedButton.icon(
                onPressed: _isLoading ? null : _guestLogin,
                icon: const Icon(Icons.person_outline),
                label: const Text("Ga verder als Gast"),
                style: OutlinedButton.styleFrom(
                  minimumSize: const Size.fromHeight(50),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
