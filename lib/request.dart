import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

// Assuming user is being imported for user details
import 'package:sictracks/user.dart';

class RequestPage extends StatefulWidget {
  const RequestPage({Key? key}) : super(key: key);

  @override
  RequestPageState createState() => RequestPageState();
}

class RequestPageState extends State<RequestPage> {
  double screenHeight = 0;
  double screenWidth = 0;

  Color primary = const Color.fromARGB(248, 59, 34, 172);
  Color secondary = const Color.fromARGB(248, 184, 42, 39);

  // Dropdown values
  String selectedReason = "";
  List<String> reasons = [
    'Emergency',
    'Medical Reason',
    'Personal Reason',
    'Other'
  ];

  // Controller for the explaination text field
  final TextEditingController explainationController = TextEditingController();

  // Store the latest request status
  String requestStatus = "";

  @override
  void initState() {
    super.initState();
    selectedReason = reasons[0]; // Default reason
    getRequestStatus();
  }

  @override
  void dispose() {
    explainationController.dispose(); // Dispose the controller when done
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    screenHeight = MediaQuery.of(context).size.height;
    screenWidth = MediaQuery.of(context).size.width;

    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'Request Outing Permission',
          style: TextStyle(
            fontSize: 25,
            color: Color.fromARGB(255, 35, 21, 159),
            fontWeight: FontWeight.bold,
          ),
        ),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pop(context); // Back to the previous page
          },
        ),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const SizedBox(height: 60),

            // Dropdown for reasons
            const Text(
              "Select Reason for Outing:",
              style: TextStyle(fontSize: 18),
            ),
            const SizedBox(height: 10),
            DropdownButtonFormField<String>(
              value: selectedReason,
              icon: const Icon(Icons.arrow_downward),
              decoration: const InputDecoration(
                border: OutlineInputBorder(),
              ),
              onChanged: (String? newValue) {
                setState(() {
                  selectedReason = newValue!;
                });
              },
              items: reasons.map<DropdownMenuItem<String>>((String value) {
                return DropdownMenuItem<String>(
                  value: value,
                  child: Text(value),
                );
              }).toList(),
            ),

            const SizedBox(height: 20),

            // Explaination Text Field
            const Text(
              "Explaination for Request:",
              style: TextStyle(fontSize: 18),
            ),
            const SizedBox(height: 10),
            TextFormField(
              controller: explainationController,
              maxLines: 4,
              decoration: const InputDecoration(
                hintText: 'Provide additional details...',
                border: OutlineInputBorder(),
              ),
            ),

            const SizedBox(height: 20),

            // Send button
            Container(
              height: 60,
              width: screenWidth,
              decoration: BoxDecoration(
                color: secondary,
                borderRadius: const BorderRadius.all(Radius.circular(25)),
              ),
              child: TextButton(
                onPressed: () {
                  sendRequest(); // Send the request when pressed
                },
                child: const Text(
                  "SEND REQUEST",
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 20,
                    letterSpacing: 2,
                  ),
                ),
              ),
            ),

            const SizedBox(height: 20),

            // Display the latest request status
            if (requestStatus.isNotEmpty)
              Container(
                padding: const EdgeInsets.all(15),
                width: screenWidth,
                decoration: BoxDecoration(
                  color: Colors.grey[200],
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Text(
                  'Latest Request Status: $requestStatus',
                  style: const TextStyle(fontSize: 18, color: Colors.black87),
                ),
              ),
          ],
        ),
      ),
    );
  }

  // Function to send request data to the backend
  void sendRequest() async {
    String url =
        "http://192.168.100.69/sictracks/lib/request.php"; // Your API endpoint

    // Check user details
    if (user.id == null) {
      showAlert('Error', 'User details are missing.');
      return;
    }

    final Map<String, dynamic> data = {
      "id": user.id,
      "name": user.name,
      "reason": selectedReason,
      "explaination": explainationController.text,
      "status": "pending",
    };

    // Print data to debug
    print("Sending data: $data");

    try {
      http.Response response = await http.post(Uri.parse(url), body: data);

      if (response.statusCode == 200) {
        var result = jsonDecode(response.body); // Parse JSON response

        // Print response for debugging
        print('Response data: ${response.body}');

        if (result['status'] == 'success') {
          showAlert(
              'Request Sent', 'Your request has been submitted successfully.');
        } else {
          showAlert(
              'Error', 'Failed to send the request: ${result['message']}');
        }
      } else {
        showAlert('Error', 'Error: ${response.statusCode}');
      }
    } catch (error) {
      print('Error: $error');
      showAlert('Error', 'Error: $error');
    }
  }

  // Function to get the latest request status for the user
  void getRequestStatus() async {
    String url =
        "http://192.168.100.69/sictracks/lib/get_request_status.php"; // Your API endpoint for getting status

    final Map<String, dynamic> data = {
      "id": user.id,
      "name": user.name,
      "reason": selectedReason,
      "explaination": explainationController.text,
      "status": "pending",
    };

    try {
      http.Response response = await http.post(Uri.parse(url), body: data);

      if (response.statusCode == 200) {
        var result = jsonDecode(response.body);
        if (result['status'] == 'success') {
          setState(() {
            requestStatus = result['request_status'] ??
                ''; // Set to an empty string if null
          });
        } else {
          // Handle other errors as needed, but skip showing 'no data' as an error
          setState(() {
            requestStatus = ''; // Clear status for other errors
          });
        }
      } else {
        setState(() {
          requestStatus = 'Error: ${response.statusCode}';
        });
      }
    } catch (error) {
      setState(() {
        requestStatus = 'Error: $error';
      });
    }
  }

  // Alert dialog function to display messages
  void showAlert(String title, String message) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text(title),
          content: Text(message),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
              },
              child: const Text('OK'),
            ),
          ],
        );
      },
    );
  }
}
