import 'dart:convert';
import 'dart:math';
import 'package:shared_preferences/shared_preferences.dart';
import 'api_service.dart';

class AuthService {
  // --- GUEST LOGIN ---
  // Automatically creates a random user so the guest can use the API
  static Future<bool> loginAsGuest() async {
    final randomId = Random().nextInt(10000);
    final guestEmail = "guest_$randomId@summamove.nl";
    final guestName = "Guest User $randomId";

    // Register this fake guest to get a real key
    String? apiKey = await ApiService.registerUser(guestEmail, guestName);

    if (apiKey != null) {
      // Save it temporarily
      ApiService.setKey(apiKey);
      return true;
    }
    return false;
  }

  // --- REGISTER (With Password) ---
  static Future<bool> register(
    String name,
    String email,
    String password,
  ) async {
    // 1. Get the Key from the API
    String? apiKey = await ApiService.registerUser(email, name);

    if (apiKey != null) {
      // 2. Save Email + Password + Key locally on the phone
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('key_$email', apiKey); // Store Key linked to Email
      await prefs.setString(
        'pass_$email',
        password,
      ); // Store Password linked to Email

      // 3. Log user in immediately
      ApiService.setKey(apiKey);
      return true;
    }
    return false;
  }

  // --- LOGIN (With Password) ---
  static Future<bool> login(String email, String password) async {
    final prefs = await SharedPreferences.getInstance();

    // 1. Check if we know this email
    String? storedPass = prefs.getString('pass_$email');
    String? storedKey = prefs.getString('key_$email');

    // 2. Check if password matches
    if (storedPass != null && storedPass == password && storedKey != null) {
      ApiService.setKey(storedKey); // Load the key into the API Service
      return true;
    }

    return false; // Wrong password or user not found on this phone
  }
}
