import 'package:flutter/widgets.dart';
import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart'; // Import url_launcher

class Emergency extends StatefulWidget {
  const Emergency({Key? key}) : super(key: key);

  @override
  EmergencyState createState() => EmergencyState();
}

class EmergencyState extends State<Emergency> {
  Color primary = const Color(0xFF3B22AC); // Purple color
  Color secondary = const Color(0xFFB82A27); // Red color
  double buttonRadius = 12.0;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'WARDEN AND STAFF',
          style: TextStyle(
            fontSize: 25,
            color: Color.fromARGB(255, 35, 21, 159),
            fontWeight: FontWeight.bold,
          ),
        ),
      ),
      body: Padding(
        padding: const EdgeInsets.all(20.0),
        child: GridView.count(
          crossAxisCount: 1,
          crossAxisSpacing: 20,
          mainAxisSpacing: 20,
          children: [
            // Warden Info Card
            WardenCard(
              imagePath: 'assets/warden1.jpg', // Update with your image path
              name: 'Pn. Wan Suriana binti Wan Ibrahim',
              position: 'Warden',
              phone: '017-6441056',
              email: 'wsuriana@uptm.edu.my',
              primaryColor: primary,
            ),
            WardenCard(
              imagePath: 'assets/admin1.jpg', // Update with your image path
              name: 'Muhamad Atif bin Muhamad Pauzi',
              position: 'Hostel Management Officer',
              phone: '013-2639036',
              email: 'atif@gapps.kptm.edu.my',
              primaryColor: primary,
            ),
          ],
        ),
      ),
    );
  }
}

class WardenCard extends StatelessWidget {
  final String imagePath;
  final String name;
  final String position;
  final String phone;
  final String email;
  final Color primaryColor;

  const WardenCard({
    Key? key,
    required this.imagePath,
    required this.name,
    required this.position,
    required this.phone,
    required this.email,
    required this.primaryColor,
  }) : super(key: key);

  // Function to launch phone dialer
  Future<void> _launchPhone(String phoneNumber) async {
    final Uri phoneUri = Uri(scheme: 'tel', path: phoneNumber);
    if (await canLaunchUrl(phoneUri)) {
      await launchUrl(phoneUri);
    } else {
      throw 'Could not launch $phoneNumber';
    }
  }

  // Function to launch email client
  Future<void> _launchEmail(String emailAddress) async {
    final Uri emailUri = Uri(
      scheme: 'mailto',
      path: emailAddress,
    );
    if (await canLaunchUrl(emailUri)) {
      await launchUrl(emailUri);
    } else {
      throw 'Could not launch $emailAddress';
    }
  }

  @override
  Widget build(BuildContext context) {
    return Card(
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12.0),
      ),
      elevation: 4,
      child: Padding(
        padding: const EdgeInsets.all(12.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            CircleAvatar(
              radius: 65,
              backgroundImage: AssetImage(imagePath),
              onBackgroundImageError: (error, stackTrace) {
                print('Error loading image: $error');
              },
            ),
            SizedBox(height: 10),
            Text(
              name,
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: primaryColor,
              ),
            ),
            Text(
              position,
              style: TextStyle(fontSize: 14, color: Colors.grey[600]),
            ),
            SizedBox(height: 8),
            GestureDetector(
              onTap: () => _launchPhone(phone), // Open phone dialer
              child: Text(
                phone,
                style: TextStyle(fontSize: 14, color: Colors.blue),
              ),
            ),
            GestureDetector(
              onTap: () => _launchEmail(email), // Open email client
              child: Text(
                email,
                style: TextStyle(fontSize: 14, color: Colors.blue),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
