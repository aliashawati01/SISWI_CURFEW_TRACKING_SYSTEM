import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:sictracks/dashboard.dart';
import 'package:sictracks/user.dart';
import 'package:shared_preferences/shared_preferences.dart';

class LoginStudent extends StatefulWidget {
  const LoginStudent({Key? key}) : super(key: key);

  @override
  LoginStudentState createState() => LoginStudentState();
}

class LoginStudentState extends State<LoginStudent> {
  TextEditingController idController = TextEditingController();
  TextEditingController passController = TextEditingController();
  double screenHeight = 0;
  double screenWidht = 0;
  String _msg = "";

  Color primary = const Color.fromARGB(248, 59, 34, 172);
  Color secondary = const Color.fromARGB(248, 184, 42, 39);

  late SharedPreferences sharePreferences;

  @override
  Widget build(BuildContext context) {
    screenHeight = MediaQuery.of(context).size.height;
    screenWidht = MediaQuery.of(context).size.width;

    return Scaffold(
      resizeToAvoidBottomInset: false,
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            colors: [
              Color.fromARGB(255, 144, 144, 144),
              Color.fromARGB(255, 171, 171, 171),
              Color.fromARGB(255, 231, 231, 231),
              Color.fromARGB(255, 231, 231, 231),
              Color.fromARGB(255, 201, 201, 201),
            ],
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
          ),
        ),
        child: Column(
          children: [
            Container(
              height: screenHeight / 3,
              width: screenWidht,
              decoration: const BoxDecoration(
                gradient: LinearGradient(
                  colors: [
                    Color(0xFF3B22AC),
                    Color.fromARGB(255, 31, 139, 185),
                    Color(0xFF3B22AC)
                  ],
                  begin: Alignment.topCenter,
                  end: Alignment.bottomCenter,
                ),
                borderRadius:
                    BorderRadius.only(bottomLeft: Radius.circular(40)),
              ),
              child: Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Image.asset(
                      'assets/UptmLogo.png',
                      fit: BoxFit.cover,
                      width: 230,
                      height: 100,
                    ),
                    const Text(
                      'SIC-Tracks',
                      style: TextStyle(
                        fontSize: 30,
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                      ),
                    ),
                  ],
                ),
              ),
            ),
            Container(
              margin: EdgeInsets.only(
                  top: screenHeight / 24, bottom: screenHeight / 20),
              child: Text(
                "Login",
                style: TextStyle(
                    fontSize: screenWidht / 18,
                    fontFamily: "NexaBold",
                    color: Colors.black),
              ),
            ),
            Container(
              alignment: Alignment.centerLeft,
              margin: EdgeInsets.symmetric(horizontal: screenWidht / 12),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  fieldTitle("Student ID"),
                  customField("Enter Student ID", idController),
                  fieldTitle("Password"),
                  customField("Enter Password", passController),
                  Container(
                    height: 60,
                    width: screenWidht,
                    margin: EdgeInsets.only(top: screenHeight / 50),
                    decoration: BoxDecoration(
                      color: secondary,
                      borderRadius: const BorderRadius.all(Radius.circular(25)),
                    ),
                    child: Center(
                      child: TextButton(
                        onPressed: () {
                          login();
                        },
                        child: Text(
                          "LOGIN",
                          style: TextStyle(
                            fontSize: screenWidht / 25,
                            fontFamily: "Schyler",
                            color: Colors.white,
                            letterSpacing: 3,
                          ),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
            Text(
              _msg,
              style: const TextStyle(fontSize: 20.0),
            ),
          ],
        ),
      ),
    );
  }

  void login() async {
    String url = "http://192.168.100.69/sictracks/web/studentlogin.php";

    final Map<String, dynamic> queryParams = {
      "id": idController.text,
      "password": passController.text,
    };

    try {
      http.Response response =
          await http.post(Uri.parse(url), body: queryParams);

      if (response.statusCode == 200) {
        var userData = jsonDecode(response.body);

        if (userData.isNotEmpty && userData[0] != null) {
          user.id = userData[0]['id'].toString();
          user.email = userData[0]['email'];
          user.name = userData[0]['name'];
          user.password = userData[0]['password'];
          user.ic = userData[0]['ic'].toString();
          user.sem = userData[0]['sem'].toString();
          user.notel = userData[0]['notel'].toString();
          user.rumah = userData[0]['rumah'];

          setState(() {
            _msg = "Welcome, ${user.name}";
          });

          Navigator.pushReplacement(
            context,
            MaterialPageRoute(builder: (context) => Dashboard()),
          );
        } else {
          setState(() {
            _msg = "ID or Password Invalid";
          });
        }
      } else {
        print("ERROR : ${response.statusCode}");
      }
    } catch (error) {
      setState(() {
        _msg = "ERROR : $error";
      });
    }
  }

  Widget fieldTitle(String title) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      child: Text(
        title,
        style: TextStyle(
          fontSize: screenWidht / 25,
          fontFamily: "Schyler",
          color: Colors.black,
        ),
      ),
    );
  }

  Widget customField(String hint, TextEditingController controller) {
    return Container(
      width: screenWidht,
      margin: const EdgeInsets.only(bottom: 15),
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.all(Radius.circular(12)),
        boxShadow: [
          BoxShadow(
            color: Colors.black26,
            blurRadius: 10,
            offset: Offset(2, 2),
          ),
        ],
      ),
      child: Row(
        children: [
          Container(
            width: screenWidht / 6,
            child: Icon(
              Icons.person,
              color: secondary,
              size: screenWidht / 12,
            ),
          ),
          Expanded(
            child: Padding(
              padding: EdgeInsets.only(right: screenWidht / 12),
              child: TextFormField(
                controller: controller,
                enableSuggestions: false,
                autocorrect: false,
                decoration: InputDecoration(
                  contentPadding: EdgeInsets.symmetric(
                    vertical: screenHeight / 35,
                  ),
                  border: InputBorder.none,
                  hintText: hint,
                ),
                maxLines: 1,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
