import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:summa_move/models/performances.dart';
import '../models/exercise.dart';

class ApiService {
  static const String baseUrl = 'https://summa-move-api.vercel.app/api';
  static String? _apiKey;

  // Allow AuthService to set the key
  static void setKey(String key) {
    _apiKey = key;
  }

  // Update registerUser to return String? (the key)
  static Future<String?> registerUser(String email, String name) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/users'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'email': email, 'name': name}),
      );

      if (response.statusCode == 200 || response.statusCode == 201) {
        final data = jsonDecode(response.body);
        return data['apiKey'];
      }
      return null;
    } catch (e) {
      return null;
    }
  }

  // ... The rest of the file (getExercises, getPerformances, etc.) remains exactly the same
  static Map<String, String> get _headers => {
    'Content-Type': 'application/json',
    'x-api-key': _apiKey ?? '',
  };

  static Future<List<Exercise>> getExercises() async {
    final response = await http.get(Uri.parse('$baseUrl/exercises'));

    if (response.statusCode == 200) {
      List jsonResponse = jsonDecode(response.body);
      return jsonResponse.map((item) => Exercise.fromJson(item)).toList();
    }
    return [];
  }

  // --- PRESTATIES OPHALEN (GET) ---
  static Future<List<Performance>> getPerformances() async {
    if (_apiKey == null) return [];

    final response = await http.get(
      Uri.parse('$baseUrl/performances'),
      headers: _headers,
    );

    if (response.statusCode == 200) {
      List jsonResponse = jsonDecode(response.body);
      return jsonResponse.map((item) => Performance.fromJson(item)).toList();
    }
    return [];
  }

  // --- PRESTATIE TOEVOEGEN (POST) ---
  static Future<bool> createPerformance(Performance p) async {
    final response = await http.post(
      Uri.parse('$baseUrl/performances'),
      headers: _headers,
      body: jsonEncode(p.toJson()),
    );
    return response.statusCode == 200 || response.statusCode == 201;
  }

  static Future<bool> updatePerformance(Performance p) async {
    // We gebruiken de ID in de URL om aan te geven welke we updaten
    final response = await http.put(
      Uri.parse('$baseUrl/performances/${p.id}'),
      headers: _headers,
      body: jsonEncode(p.toJson()),
    );
    return response.statusCode == 200;
  }

  // --- PRESTATIE VERWIJDEREN (DELETE) ---
  static Future<bool> deletePerformance(String id) async {
    final response = await http.delete(
      Uri.parse('$baseUrl/performances/$id'),
      headers: _headers,
    );
    return response.statusCode == 200;
  }
}
