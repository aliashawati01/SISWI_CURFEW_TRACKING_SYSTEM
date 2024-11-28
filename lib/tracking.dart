import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:sictracks/user.dart';

class Tracking extends StatefulWidget {
  @override
  _TrackingState createState() => _TrackingState();
}

class _TrackingState extends State<Tracking> {
  List<Map<String, dynamic>> scanHistory = [];

  @override
  void initState() {
    super.initState();
    fetchScanHistory(); // Fetch the student's scan history upon initialization
  }

  Future<void> fetchScanHistory() async {
    try {
      final response = await http.post(
        Uri.parse('http://192.168.100.69/sictracks/lib/getStudentHistory.php'),
        body: {'id': user.id},
      );

      if (response.statusCode == 200) {
        var jsonResponse = json.decode(response.body);
        setState(() {
          scanHistory = List<Map<String, dynamic>>.from(jsonResponse);
        });
      } else {
        print('Error fetching data: ${response.statusCode}');
      }
    } catch (e) {
      print('Request failed with error: $e');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Scan History")),
      body: SingleChildScrollView(
        scrollDirection: Axis.horizontal,
        child: DataTable(
          columns: const [
            DataColumn(label: Text('Student ID')),
            DataColumn(label: Text('Date')),
            DataColumn(label: Text('Check-In')),
            DataColumn(label: Text('Check-Out')),
          ],
          rows: scanHistory.map((scan) {
            return DataRow(cells: [
              DataCell(Text(scan['id'].toString())), // Student ID
              DataCell(Text(scan['date'] ?? '')), // Date
              DataCell(Text(scan['checkin'] ?? '--/--')), // Check-In
              DataCell(Text(scan['checkout'] ?? '--/--')), // Check-Out
            ]);
          }).toList(),
        ),
      ),
    );
  }
}
