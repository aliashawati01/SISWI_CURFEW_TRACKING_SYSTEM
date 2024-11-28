import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:sictracks/Homepage.dart';
import 'package:sictracks/other.dart';
import 'package:sictracks/profile.dart';

class Dashboard extends StatefulWidget {
  const Dashboard({Key? key}) : super(key: key);

  @override
  DashboardState createState() => DashboardState();
}

class DashboardState extends State<Dashboard> {
  double screenHeight = 0;
  double screenWidht = 0;

  Color primary = const Color.fromARGB(248, 59, 34, 172);
  Color secondary = const Color.fromARGB(248, 184, 42, 39);

  int currentIndex = 1;

  List<IconData> navigationIcons = [
    FontAwesomeIcons.ellipsis,
    FontAwesomeIcons.house,
    FontAwesomeIcons.user,
  ];

  @override
  Widget build(BuildContext context) {
    screenHeight = MediaQuery.of(context).size.height;
    screenWidht = MediaQuery.of(context).size.width;

    return Scaffold(
      body: IndexedStack(
        index: currentIndex,
        children: const [Other(), Homepage(), Profile()],
      ),
      bottomNavigationBar: Container(
        height: 75,
        margin: const EdgeInsets.only(),
        decoration: BoxDecoration(
          color: primary,
          boxShadow: const [
            BoxShadow(
                color: Colors.black26, blurRadius: 10, offset: Offset(2, 2))
          ],
        ),
        child: ClipRRect(
          borderRadius: const BorderRadius.only(
              topLeft: Radius.circular(20), topRight: Radius.circular(20)),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.center,
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              for (int i = 0; i < navigationIcons.length; i++) ...<Expanded>{
                Expanded(
                  child: GestureDetector(
                    onTap: () {
                      setState(() {
                        currentIndex =
                            i; // Set index dynamically based on tapped icon
                      });
                    },
                    child: Container(
                      height: screenHeight,
                      width: screenWidht,
                      color: primary,
                      child: Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(
                              navigationIcons[i],
                              color: i == currentIndex
                                  ? Colors.white
                                  : Color.fromARGB(255, 146, 38, 255),
                              size: i == currentIndex ? 31 : 27,
                            ),
                            i == currentIndex
                                ? Container(
                                    margin: const EdgeInsets.only(
                                      top: 6,
                                    ),
                                    height: 3,
                                    width: 28,
                                    decoration: const BoxDecoration(
                                        borderRadius: BorderRadius.all(
                                            Radius.circular(40)),
                                        color: Colors.white),
                                  )
                                : const SizedBox()
                          ],
                        ),
                      ),
                    ),
                  ),
                ),
              }
            ],
          ),
        ),
      ),
    );
  }
}
