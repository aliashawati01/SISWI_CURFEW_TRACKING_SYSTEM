import 'package:flutter/material.dart';
import 'package:sictracks/Emergency.dart';
import 'package:sictracks/faq.dart';
import 'package:sictracks/tracking.dart';
import 'request.dart'; // Import your Request page

class Other extends StatefulWidget {
  const Other({Key? key}) : super(key: key);

  @override
  OtherState createState() => OtherState();
}

class OtherState extends State<Other> {
  Color primary = const Color(0xFF3B22AC); // Purple color
  Color secondary = const Color(0xFFB82A27); // Red color
  double buttonRadius = 12.0; // Rounded corner radius for buttons

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'SICTrackS',
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
          crossAxisCount: 2,
          crossAxisSpacing: 20,
          mainAxisSpacing: 20,
          children: [
            _buildActionButton(
              icon: Icons.history,
              label: 'Tracking History',
              color: Colors.blue,
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => Tracking()),
                );
              },
            ),
            _buildActionButton(
              icon: Icons.outdoor_grill,
              label: 'Request Outing',
              color: Colors.green,
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => const RequestPage()),
                );
              },
            ),
            _buildActionButton(
              icon: Icons.phone,
              label: 'Emergency Number',
              color: Colors.orange,
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => const Emergency()),
                );
              },
            ),
            _buildActionButton(
              icon: Icons.question_answer,
              label: 'FAQ',
              color: const Color(0xFFC69244),
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => const FAQ()),
                );
              },
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildActionButton({
    required IconData icon,
    required String label,
    required Color color,
    required VoidCallback onPressed,
  }) {
    return ElevatedButton(
      style: ElevatedButton.styleFrom(
        backgroundColor: color, // Use backgroundColor instead of primary
        padding: const EdgeInsets.symmetric(vertical: 20),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(buttonRadius),
        ),
      ),
      onPressed: onPressed,
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(icon, size: 40, color: Colors.white),
          const SizedBox(height: 10),
          Text(
            label,
            textAlign: TextAlign.center,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 16,
              fontWeight: FontWeight.bold,
            ),
          ),
        ],
      ),
    );
  }
}
