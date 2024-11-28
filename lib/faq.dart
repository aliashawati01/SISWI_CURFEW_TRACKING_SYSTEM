import 'package:flutter/material.dart';

class FAQ extends StatefulWidget {
  const FAQ({Key? key}) : super(key: key);

  @override
  FAQState createState() => FAQState();
}

class FAQState extends State<FAQ> {
  Color primary = const Color(0xFF3B22AC); // Purple color
  Color secondary = const Color(0xFFB82A27); // Red color
  double buttonRadius = 12.0; // Rounded corner radius for buttons

  // List of questions and answers
  final List<Map<String, String>> faqData = [
    {
      'question': 'What does the QR code contain?',
      'answer':
          'The QR code includes your unique student ID along with the current timestamp at the time of generation. This combination ensures that each QR code is unique and time-sensitive for secure check-ins and check-outs.'
    },
    {
      'question': 'How long does the QR code last?',
      'answer':
          'The QR code is valid for 1 minute before it automatically regenerates. This frequent update helps prevent unauthorized use and ensures that only the latest QR code can be scanned for check-ins and check-outs.'
    },
    {
      'question': 'Can I check my scan history?',
      'answer':
          'Yes, your scan history is available within the app on the tracking page. You can review all your past check-ins and check-outs here, which helps you keep track of your own curfew activity over time.'
    },
    {
      'question': 'What is the purpose of the SISWI system?',
      'answer':
          'The SISWI system is designed to efficiently track student check-ins and check-outs at the hostel using QR code technology. It promotes accountability and safety by logging each entry and exit with accurate timestamps.'
    },
    {
      'question':
          'Why does the system record both check-in and check-out times?',
      'answer':
          'The system logs both check-ins and check-outs to accurately track entry and exit activity. This data helps to ensure student safety and allows the hostel to monitor curfew compliance.'
    },
    {
      'question': ' Can I receive notifications for curfew reminders?',
      'answer':
          ' Yes, the app is set up to send you a push notification 30 minutes before curfew time, reminding you to return if you’re outside the hostel. Make sure notifications are enabled on your device to receive these reminders.'
    },
    {
      'question': 'How do I generate a QR code for check-in?',
      'answer':
          'Open the app and navigate to the homepage. A new QR code with your ID and current timestamp will automatically appear, ready for scanning by the system.'
    },
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'FAQ',
          style: TextStyle(
            fontSize: 25,
            color: Color.fromARGB(255, 183, 178, 228),
            fontWeight: FontWeight.bold,
          ),
        ),
        backgroundColor: primary,
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(10),
        itemCount: faqData.length,
        itemBuilder: (BuildContext context, int index) {
          return Card(
            margin: const EdgeInsets.symmetric(vertical: 10),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(buttonRadius),
            ),
            child: ExpansionTile(
              title: Text(
                faqData[index]['question']!,
                style: const TextStyle(
                  fontWeight: FontWeight.bold,
                  fontSize: 18,
                ),
              ),
              children: <Widget>[
                Padding(
                  padding: const EdgeInsets.all(10),
                  child: Text(
                    faqData[index]['answer']!,
                    style: const TextStyle(fontSize: 16),
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}
