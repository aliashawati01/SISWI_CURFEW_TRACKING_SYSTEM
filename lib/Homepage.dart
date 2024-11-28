import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'dart:async'; // For Timer
import 'package:qr_flutter/qr_flutter.dart';
import 'package:http/http.dart' as http;
import 'package:sictracks/user.dart';

class Homepage extends StatefulWidget {
  const Homepage({Key? key}) : super(key: key);

  @override
  HomepageState createState() => HomepageState();
}

class HomepageState extends State<Homepage> {
  double screenHeight = 0;
  double screenWidth = 0;

  Color primary = const Color.fromARGB(248, 59, 34, 172);
  Color secondary = const Color.fromARGB(248, 184, 42, 39);

  String qrData = ''; // QR data to hold the generated QR code
  Timer? _timer;

  // Variables for latest check-in and check-out
  String checkInTime = "--:--:--";
  String checkOutTime = "--:--:--";

  @override
  void initState() {
    super.initState();
    _generateQRCode();
    _startTimer();
    _fetchUserActivity(); // Load initial user activity
  }

  // Method to generate QR Code with student ID and timestamp
  void _generateQRCode() {
    if (mounted) {
      setState(() {
        qrData = user.id + '-' + DateTime.now().toIso8601String();
      });
    }
  }

  // Timer to update QR code every minute
  void _startTimer() {
    _timer = Timer.periodic(const Duration(minutes: 1), (timer) {
      if (mounted) {
        _generateQRCode();
      }
    });
  }

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    _fetchUserActivity(); // Fetch data every time the widget rebuilds
  }

  @override
  void dispose() {
    _timer?.cancel(); // Cancel the timer when the widget is disposed
    super.dispose();
  }

  // Fetch the latest check-in and check-out times from backend
  Future<void> _fetchUserActivity() async {
    try {
      final response = await http.post(
        Uri.parse('http://192.168.100.69/sictracks/lib/getLatestAct.php'),
        body: {'id': user.id},
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);

        // Debug the received data
        print('Fetched data: $data');

        if (mounted) {
          setState(() {
            // Use default '--:--:--' if no valid data is present
            checkInTime = data['checkin'] ?? '--:--:--';
            checkOutTime = data['checkout'] ?? '--:--:--';
          });
        }
      } else {
        throw Exception('Failed to load activity');
      }
    } catch (e) {
      print('Error fetching activity: $e');

      // Display placeholders in case of error
      setState(() {
        checkInTime = '--:--:--';
        checkOutTime = '--:--:--';
      });
    }
  }

  // Refresh user data on pull-to-refresh
  Future<void> _refreshData() async {
    await _fetchUserActivity();
    _generateQRCode();
  }

  @override
  Widget build(BuildContext context) {
    screenHeight = MediaQuery.of(context).size.height;
    screenWidth = MediaQuery.of(context).size.width;

    return Scaffold(
      body: RefreshIndicator(
        onRefresh: _refreshData, // Attach the refresh method
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(20),
          child: Column(
            children: [
              // Welcome section
              Container(
                alignment: Alignment.centerLeft,
                margin: const EdgeInsets.only(top: 30),
                child: Text(
                  "Welcome",
                  style: TextStyle(
                    fontSize: screenWidth / 12,
                    color: Colors.black,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
              Container(
                alignment: Alignment.centerLeft,
                child: Text(
                  "STUDENT " + user.name, // Display user name
                  style: TextStyle(
                    fontSize: screenWidth / 20,
                    color: Colors.black,
                  ),
                ),
              ),

              // Today's status section
              Container(
                alignment: Alignment.centerLeft,
                margin: const EdgeInsets.only(top: 30),
                child: Text(
                  "Today's status",
                  style: TextStyle(
                    fontSize: screenWidth / 16,
                    color: Colors.black,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
              Container(
                height: 150,
                margin: const EdgeInsets.only(top: 5, bottom: 30),
                decoration: const BoxDecoration(
                  color: Colors.white,
                  boxShadow: [
                    BoxShadow(
                        color: Colors.black,
                        blurRadius: 10,
                        offset: Offset(2, 2))
                  ],
                  borderRadius: BorderRadius.all(Radius.circular(20)),
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    Expanded(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        crossAxisAlignment: CrossAxisAlignment.center,
                        children: [
                          const Text(
                            "Check-Out",
                            style: TextStyle(
                              fontWeight: FontWeight.bold,
                              fontSize: 20,
                              color: Colors.black,
                            ),
                          ),
                          Text(
                            checkOutTime,
                            style: const TextStyle(
                                fontSize: 18, color: Colors.black),
                          ),
                        ],
                      ),
                    ),
                    Expanded(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        crossAxisAlignment: CrossAxisAlignment.center,
                        children: [
                          const Text(
                            "Check-In",
                            style: TextStyle(
                              fontWeight: FontWeight.bold,
                              fontSize: 20,
                              color: Colors.black,
                            ),
                          ),
                          Text(
                            checkInTime,
                            style: const TextStyle(
                                fontSize: 18, color: Colors.black),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),

              // Date and time section with Kuala Lumpur timezone
              Container(
                alignment: Alignment.centerLeft,
                child: RichText(
                  text: TextSpan(
                    text: DateTime.now().day.toString(),
                    style: const TextStyle(color: Colors.black, fontSize: 20),
                    children: [
                      TextSpan(
                        text: DateFormat(' MMMM yyyy').format(DateTime.now()),
                        style:
                            const TextStyle(color: Colors.black, fontSize: 20),
                      ),
                    ],
                  ),
                ),
              ),
              StreamBuilder(
                stream: Stream.periodic(const Duration(seconds: 1)),
                builder: (context, snapshot) {
                  // Adjust for Kuala Lumpur timezone (UTC +8)
                  DateTime now =
                      DateTime.now().toUtc().add(const Duration(hours: 8));
                  return Container(
                    alignment: Alignment.centerLeft,
                    child: Text(
                      DateFormat('hh:mm:ss a').format(now),
                      style: const TextStyle(
                        fontSize: 22,
                        color: Color.fromARGB(255, 81, 81, 81),
                      ),
                    ),
                  );
                },
              ),

              Container(
                height: 300,
                margin: const EdgeInsets.only(top: 15, bottom: 30),
                decoration: const BoxDecoration(
                  color: Colors.white,
                  boxShadow: [
                    BoxShadow(
                        color: Colors.black,
                        blurRadius: 10,
                        offset: Offset(2, 2))
                  ],
                  borderRadius: BorderRadius.all(Radius.circular(20)),
                ),
                child: QrImageView(
                  data: qrData, // The generated QR code data
                  size: 300,
                  version: QrVersions.auto,
                  errorCorrectionLevel: QrErrorCorrectLevel.H,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
