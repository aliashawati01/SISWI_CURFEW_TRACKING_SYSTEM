import 'package:flutter/material.dart';
import 'package:sictracks/user.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class EditProfile extends StatefulWidget {
  const EditProfile({Key? key}) : super(key: key);

  @override
  EditProfileState createState() => EditProfileState();
}

class EditProfileState extends State<EditProfile> {
  double screenHeight = 0;
  double screenWidth = 0;

  Color primary = const Color.fromARGB(248, 59, 34, 172);
  Color secondary = const Color.fromARGB(248, 184, 42, 39);

  // TextEditingControllers for each field
  TextEditingController idController = TextEditingController(text: user.id);
  TextEditingController emailController =
      TextEditingController(text: user.email);
  TextEditingController nameController = TextEditingController(text: user.name);
  TextEditingController passwordController =
      TextEditingController(text: user.password);
  TextEditingController icController = TextEditingController(text: user.ic);
  TextEditingController semController = TextEditingController(text: user.sem);
  TextEditingController notelController =
      TextEditingController(text: user.notel);
  TextEditingController rumahController =
      TextEditingController(text: user.rumah);

  @override
  Widget build(BuildContext context) {
    screenHeight = MediaQuery.of(context).size.height;
    screenWidth = MediaQuery.of(context).size.width;

    return Scaffold(
      appBar: AppBar(
        title: Text("Edit Profile"),
        leading: IconButton(
          icon: Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pop(context);
          },
        ),
      ),
      backgroundColor: Colors.white,
      body: SingleChildScrollView(
        padding: EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            // Profile Picture
            Container(
              margin: EdgeInsets.only(top: 80, bottom: 24),
              height: 120,
              width: 120,
              alignment: Alignment.center,
              decoration: BoxDecoration(
                borderRadius:
                    BorderRadius.circular(60), // Circular profile image
                color: secondary,
              ),
              child: Icon(
                Icons.person,
                color: Colors.white,
                size: 76,
              ),
            ),
            Text(
              "Student ID: ${user.id}",
              style: const TextStyle(
                fontSize: 22,
                fontWeight: FontWeight.bold,
                color: Colors.black,
              ),
            ),
            const SizedBox(height: 10),
            const Text(
              "Profile Details",
              style: TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.w500,
                color: Colors.black54,
              ),
            ),
            const SizedBox(height: 24),

            // Text Fields
            _buildTextField("Student ID", idController, readOnly: true),
            _buildTextField("Student Email", emailController),
            _buildTextField("Student Name", nameController),
            _buildTextField("Password", passwordController, isPassword: true),
            _buildTextField("Identity Card", icController, readOnly: true),
            _buildTextField("Semester", semController),
            _buildTextField("Phone Number", notelController),
            _buildTextField("Room Number", rumahController),

            const SizedBox(height: 20),

            // Save Button
            _buildButton("SAVE", secondary, updateProfile),
          ],
        ),
      ),
    );
  }

  // Build a text field widget
  Widget _buildTextField(String title, TextEditingController controller,
      {bool readOnly = false, bool isPassword = false}) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          title,
          style: TextStyle(color: Colors.black87, fontWeight: FontWeight.w600),
        ),
        const SizedBox(height: 8),
        TextFormField(
          controller: controller,
          obscureText: isPassword, // Hide password text
          readOnly: readOnly,
          cursorColor: primary,
          decoration: InputDecoration(
            hintText: 'Enter $title',
            hintStyle: TextStyle(color: Colors.grey),
            enabledBorder: OutlineInputBorder(
              borderSide: BorderSide(color: primary),
              borderRadius: BorderRadius.circular(15),
            ),
            focusedBorder: OutlineInputBorder(
              borderSide: BorderSide(color: secondary),
              borderRadius: BorderRadius.circular(15),
            ),
            contentPadding: EdgeInsets.symmetric(vertical: 15, horizontal: 20),
          ),
        ),
        const SizedBox(height: 12),
      ],
    );
  }

  // Build a button widget
  Widget _buildButton(String label, Color color, VoidCallback onPressed) {
    return Container(
      height: 60,
      width: screenWidth,
      margin: EdgeInsets.only(top: screenHeight / 50),
      decoration: BoxDecoration(
        color: color,
        borderRadius: BorderRadius.all(Radius.circular(25)),
      ),
      child: Center(
        child: TextButton(
          onPressed: onPressed,
          child: Text(
            label,
            style: TextStyle(
              fontSize: screenWidth / 25,
              fontFamily: "Schyler",
              color: Colors.white,
              letterSpacing: 3,
            ),
          ),
        ),
      ),
    );
  }

  // Update profile function
  void updateProfile() async {
    String url =
        "http://192.168.100.69/sictracks/lib/updateProfile.php"; // Your API endpoint

    final Map<String, dynamic> data = {
      "id": idController.text,
      "email": emailController.text,
      "name": nameController.text,
      "password": passwordController.text,
      "ic": icController.text,
      "sem": semController.text,
      "notel": notelController.text,
      "rumah": rumahController.text,
    };

    try {
      http.Response response = await http.post(Uri.parse(url), body: data);
      if (response.statusCode == 200) {
        var result = jsonDecode(response.body);
        if (result['status'] == 'success') {
          setState(() {
            user.email = emailController.text;
            user.name = nameController.text;
            user.password = passwordController.text;
            user.ic = icController.text;
            user.sem = semController.text;
            user.notel = notelController.text;
            user.rumah = rumahController.text;
          });
          showSuccessDialog(); // Show success dialog
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text('Failed to update profile')));
        }
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Error: ${response.statusCode}')));
      }
    } catch (error) {
      ScaffoldMessenger.of(context)
          .showSnackBar(SnackBar(content: Text('Error: $error')));
    }
  }

  // Show a success dialog when profile update is successful
  void showSuccessDialog() {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text("Edit Successful"),
          content: Text("Your profile has been updated successfully."),
          actions: <Widget>[
            TextButton(
              child: Text("OK"),
              onPressed: () {
                Navigator.of(context).pop(); // Close the dialog
              },
            ),
          ],
        );
      },
    );
  }
}
