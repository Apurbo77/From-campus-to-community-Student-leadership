import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'blood_matching_screen.dart';
import 'donation_camps_screen.dart';
import 'training_programs_screen.dart';
import 'certification_screen.dart';
import 'leaderboard_screen.dart';
import 'our_donors_screen.dart';

class ServicesTab extends StatelessWidget {
  const ServicesTab({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(
          'Services',
          style: GoogleFonts.inter(fontWeight: FontWeight.bold),
        ),
        centerTitle: true,
        backgroundColor: Colors.transparent,
        elevation: 0,
        surfaceTintColor: Colors.transparent,
      ),
      backgroundColor: const Color(0xFFF8F9FA),
      body: GridView.count(
        crossAxisCount: 2,
        padding: const EdgeInsets.all(24),
        crossAxisSpacing: 16,
        mainAxisSpacing: 16,
        children: [
          _buildServiceCard(
            'Blood\nMatching',
            Icons.bloodtype,
            Colors.redAccent,
            () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (_) => const BloodMatchingScreen(),
                ),
              );
            },
          ),
          _buildServiceCard(
            'Donation\nCamps',
            Icons.campaign,
            Colors.orange,
            () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (_) => const DonationCampsScreen(),
                ),
              );
            },
          ),
          _buildServiceCard(
            'Training\nPrograms',
            Icons.model_training,
            Colors.blue,
            () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (_) => const TrainingProgramsScreen(),
                ),
              );
            },
          ),
          _buildServiceCard(
            'Apply\nCertificate',
            Icons.workspace_premium,
            Colors.purple,
            () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (_) => const CertificationScreen(),
                ),
              );
            },
          ),
          _buildServiceCard(
            'Leaderboard',
            Icons.leaderboard,
            Colors.amber,
            () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (_) => const LeaderboardScreen(),
                ),
              );
            },
          ),
          _buildServiceCard(
            'Our\nDonors',
            Icons.groups,
            Colors.teal,
            () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (_) => const OurDonorsScreen(),
                ),
              );
            },
          ),
        ],
      ),
    );
  }

  Widget _buildServiceCard(
      String title, IconData icon, Color color, VoidCallback onTap) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(16),
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: color.withOpacity(0.1),
              blurRadius: 15,
              offset: const Offset(0, 5),
            ),
          ],
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: color.withOpacity(0.15),
                shape: BoxShape.circle,
              ),
              child: Icon(icon, color: color, size: 32),
            ),
            const SizedBox(height: 12),
            Text(
              title,
              textAlign: TextAlign.center,
              style: GoogleFonts.inter(
                fontSize: 14,
                fontWeight: FontWeight.w600,
                color: const Color(0xFF1A1A1A),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
