import 'package:flutter/material.dart';
import 'package:sictracks/user.dart';
import 'package:sictracks/editProfile.dart';
import 'package:sictracks/LoginStudent.dart';
import 'package:shared_preferences/shared_preferences.dart';

class Profile extends StatefulWidget {
  const Profile({Key? key}) : super(key: key);

  @override
  ProfileState createState() => ProfileState();
}

class ProfileState extends State<Profile> {
  Color primary = const Color.fromARGB(248, 59, 34, 172);
  Color secondary = const Color.fromARGB(248, 184, 42, 39);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        child: Center(
          child: Padding(
            padding: const EdgeInsets.all(20.0),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                // Profile Icon using an image
                Container(
                  margin: EdgeInsets.only(bottom: 24),
                  height: 150,
                  width: 150,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                  ),
                  child: ClipOval(
                    child: Image.asset(
                      'assets/sictrackslogo.png',
                      // Path to your image
                      fit: BoxFit.cover,
                    ),
                  ),
                ),
                SizedBox(height: 20),

                // User Details
                _buildTextField("Student ID", user.id),
                _buildTextField("Name", user.name),
                _buildTextField("Email", user.email),

                SizedBox(height: 30),

                // Edit Profile Button with Blue RGB Color
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(builder: (context) => EditProfile()),
                      );
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor:
                          Color.fromARGB(248, 59, 34, 172), // Blue in RGB
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(15),
                      ),
                      padding: EdgeInsets.symmetric(vertical: 15),
                    ),
                    child: Text(
                      "Edit Profile",
                      style: TextStyle(fontSize: 18, color: Colors.white),
                    ),
                  ),
                ),

                SizedBox(height: 10),

                // Logout Button with Red Color
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: logout,
                    style: ElevatedButton.styleFrom(
                      backgroundColor:
                          Color.fromARGB(248, 184, 42, 39), // Red color
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(15),
                      ),
                      padding: EdgeInsets.symmetric(vertical: 15),
                    ),
                    child: Text(
                      "Logout",
                      style: TextStyle(fontSize: 18, color: Colors.white),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  // Build Text Field for Display
  Widget _buildTextField(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 10),
      child: TextField(
        readOnly: true,
        decoration: InputDecoration(
          labelText: label,
          labelStyle: TextStyle(color: Color.fromARGB(136, 39, 0, 0)),
          hintText: value,
          hintStyle: TextStyle(color: const Color.fromARGB(255, 0, 0, 0)),
          filled: true,
          fillColor: const Color.fromARGB(255, 255, 255, 255),
          border: OutlineInputBorder(
            borderSide: BorderSide(color: Color.fromARGB(135, 149, 0, 0)),
            borderRadius: BorderRadius.circular(15),
          ),
        ),
        style: TextStyle(color: Colors.black),
      ),
    );
  }

  // Logout function
  void logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.clear();

    Navigator.pushReplacement(
      context,
      MaterialPageRoute(builder: (context) => LoginStudent()),
    );
  }
}
