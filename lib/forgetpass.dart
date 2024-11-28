import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class ForgotPasswordScreen extends StatefulWidget {
  @override
  _ForgotPasswordScreenState createState() => _ForgotPasswordScreenState();
}

class _ForgotPasswordScreenState extends State<ForgotPasswordScreen> {
  TextEditingController emailController = TextEditingController();
  String _message = "";

  void resetPassword() async {
    String url = "http://192.168.100.69/sictracks/lib/resetpass.php";

    final Map<String, dynamic> queryParams = {
      "email": emailController.text,
    };

    try {
      http.Response response =
          await http.post(Uri.parse(url), body: queryParams);

      if (response.statusCode == 200) {
        var responseData = jsonDecode(response.body);
        setState(() {
          _message = responseData['message'];
        });
      } else {
        setState(() {
          _message = "Error: ${response.statusCode}";
        });
      }
    } catch (error) {
      setState(() {
        _message = "Error: $error";
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Forgot Password"),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text(
              "Enter your email to reset password",
              style: TextStyle(fontSize: 20),
            ),
            TextField(
              controller: emailController,
              decoration: InputDecoration(labelText: "Email"),
            ),
            SizedBox(height: 20),
            ElevatedButton(
              onPressed: resetPassword,
              child: Text("Reset Password"),
            ),
            SizedBox(height: 20),
            Text(
              _message,
              style: TextStyle(color: Colors.red, fontSize: 16),
            ),
          ],
        ),
      ),
    );
  }
}
