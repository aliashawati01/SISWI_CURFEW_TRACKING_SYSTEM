import 'package:flutter/material.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:timezone/data/latest.dart' as tz;
import 'package:timezone/timezone.dart' as tz;
import 'package:sictracks/LoginStudent.dart';
import 'package:sictracks/dashboard.dart';
import 'package:sictracks/user.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:permission_handler/permission_handler.dart';

void main() {
  runApp(const MyApp());
  tz.initializeTimeZones();
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'SICTRACKS',
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.deepPurple),
        useMaterial3: true,
      ),
      home: const AuthCheck(),
    );
  }
}

class AuthCheck extends StatefulWidget {
  const AuthCheck({Key? key}) : super(key: key);

  @override
  AuthCheckState createState() => AuthCheckState();
}

class AuthCheckState extends State<AuthCheck> {
  bool userAvailable = false;
  late SharedPreferences sharedPreferences;
  late FlutterLocalNotificationsPlugin flutterLocalNotificationsPlugin;

  @override
  void initState() {
    super.initState();
    initializeNotifications().then((_) {
      // After successful initialization, schedule the notification
      scheduleCurfewNotification();
    });
  }

  void getCurrentUser() async {
    sharedPreferences = await SharedPreferences.getInstance();
    String? userId = sharedPreferences.getString('id');

    if (userId != null) {
      setState(() {
        user.id = userId;
        userAvailable = true;
      });

      // Initialize notifications before scheduling
      await initializeNotifications();

      // Schedule notification after user is available
      scheduleCurfewNotification();
    }
  }

  Future<void> initializeNotifications() async {
    tz.initializeTimeZones(); // Initialize timezone data

    flutterLocalNotificationsPlugin = FlutterLocalNotificationsPlugin();

    var androidInitSettings =
        const AndroidInitializationSettings('@mipmap/sictrackslogo');
    var initSettings = InitializationSettings(android: androidInitSettings);

    // Initialize the plugin
    await flutterLocalNotificationsPlugin.initialize(initSettings);

    // Create a notification channel (required on Android 8.0+)
    const AndroidNotificationChannel channel = AndroidNotificationChannel(
      'curfew_channel_id', // ID of the channel
      'Curfew Notifications', // Name of the channel
      description: 'Notification channel for curfew reminders',
      importance: Importance.max,
    );

    final androidPlugin =
        flutterLocalNotificationsPlugin.resolvePlatformSpecificImplementation<
            AndroidFlutterLocalNotificationsPlugin>();
    if (androidPlugin != null) {
      await androidPlugin.createNotificationChannel(channel);

      // Check and request notification permission
      if (await Permission.notification.isDenied) {
        // Request notification permission
        PermissionStatus status = await Permission.notification.request();
        if (!status.isGranted) {
          print("Notification permission not granted. Exiting.");
          return;
        }
      }
    }
  }

  void scheduleCurfewNotification() async {
    print("Scheduling notification...");

    var androidDetails = const AndroidNotificationDetails(
      'curfew_channel_id', // Channel ID
      'Curfew Notifications', // Channel name
      channelDescription: 'Notification channel for curfew reminders',
      importance: Importance.max,
      priority: Priority.high,
    );

    var notificationDetails = NotificationDetails(android: androidDetails);

    final kualaLumpur = tz.getLocation('Asia/Kuala_Lumpur');
    final now = tz.TZDateTime.now(kualaLumpur);
    var scheduledTime =
        tz.TZDateTime(kualaLumpur, now.year, now.month, now.day, 23, 35);

    if (scheduledTime.isBefore(now)) {
      scheduledTime = scheduledTime.add(const Duration(days: 1));
    }

    print("Attempting to schedule notification for: $scheduledTime");

    await flutterLocalNotificationsPlugin.zonedSchedule(
      0, // Notification ID
      'Curfew Reminder', // Notification title
      'Curfew time in 30 minutes', // Notification body
      scheduledTime,
      notificationDetails,
      androidAllowWhileIdle: true,
      uiLocalNotificationDateInterpretation:
          UILocalNotificationDateInterpretation.absoluteTime,
      matchDateTimeComponents: DateTimeComponents.time,
    );

    print("Notification scheduled successfully.");
  }

  @override
  Widget build(BuildContext context) {
    return userAvailable ? const Dashboard() : const LoginStudent();
  }
}
