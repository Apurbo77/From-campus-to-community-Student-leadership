import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class OurDonorsScreen extends StatefulWidget {
  const OurDonorsScreen({super.key});

  @override
  State<OurDonorsScreen> createState() => _OurDonorsScreenState();
}

class _OurDonorsScreenState extends State<OurDonorsScreen> {
  List<dynamic> _donors = [];
  bool _isLoading = true;
  String? _errorMessage;
  String _filterBloodGroup = 'All';

  final List<String> bloodGroups = [
    'All',
    'O+',
    'O-',
    'A+',
    'A-',
    'B+',
    'B-',
    'AB+',
    'AB-'
  ];

  static const String backendUrl = 'http://192.168.1.100/api/get_donors.php';

  @override
  void initState() {
    super.initState();
    _fetchDonors();
  }

  Future<void> _fetchDonors() async {
    try {
      final response = await http
          .get(Uri.parse(backendUrl))
          .timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final decodedResponse = jsonDecode(response.body);
        setState(() {
          _donors = decodedResponse is List ? decodedResponse : [];
          _isLoading = false;
        });
      } else {
        setState(() {
          _errorMessage = 'Failed to fetch donors';
          _isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        _errorMessage = 'Error: ${e.toString()}';
        _isLoading = false;
      });
    }
  }

  List<dynamic> getFilteredDonors() {
    if (_filterBloodGroup == 'All') {
      return _donors;
    }
    return _donors
        .where((donor) => donor['blood_group'] == _filterBloodGroup)
        .toList();
  }

  @override
  Widget build(BuildContext context) {
    final filteredDonors = getFilteredDonors();

    return Scaffold(
      appBar: AppBar(
        title: Text(
          'Our Donors',
          style: GoogleFonts.inter(fontWeight: FontWeight.bold),
        ),
        centerTitle: true,
        backgroundColor: Colors.transparent,
        elevation: 0,
        surfaceTintColor: Colors.transparent,
      ),
      backgroundColor: const Color(0xFFF8F9FA),
      body: Column(
        children: [
          // Filter dropdown
          Padding(
            padding: const EdgeInsets.all(16),
            child: DropdownButtonFormField<String>(
              value: _filterBloodGroup,
              onChanged: (String? value) {
                if (value != null) {
                  setState(() => _filterBloodGroup = value);
                }
              },
              items: bloodGroups.map((String group) {
                return DropdownMenuItem<String>(
                  value: group,
                  child: Text('Blood Group: $group'),
                );
              }).toList(),
              decoration: InputDecoration(
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
                contentPadding:
                    const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              ),
            ),
          ),
          // Donor list
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : _errorMessage != null
                    ? Center(
                        child: Text(
                          _errorMessage!,
                          style: GoogleFonts.inter(color: Colors.red),
                        ),
                      )
                    : filteredDonors.isEmpty
                        ? Center(
                            child: Text(
                              'No donors found',
                              style: GoogleFonts.inter(color: Colors.grey),
                            ),
                          )
                        : ListView.builder(
                            padding: const EdgeInsets.symmetric(horizontal: 16),
                            itemCount: filteredDonors.length,
                            itemBuilder: (context, index) {
                              final donor = filteredDonors[index];
                              return Container(
                                margin: const EdgeInsets.only(bottom: 12),
                                padding: const EdgeInsets.all(16),
                                decoration: BoxDecoration(
                                  color: Colors.white,
                                  borderRadius: BorderRadius.circular(12),
                                  boxShadow: [
                                    BoxShadow(
                                      color: Colors.black.withOpacity(0.08),
                                      blurRadius: 10,
                                    ),
                                  ],
                                ),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Expanded(
                                          child: Column(
                                            crossAxisAlignment:
                                                CrossAxisAlignment.start,
                                            children: [
                                              Text(
                                                donor['full_name'] ??
                                                    'Unknown Donor',
                                                style: GoogleFonts.inter(
                                                  fontSize: 16,
                                                  fontWeight: FontWeight.w600,
                                                ),
                                              ),
                                              const SizedBox(height: 4),
                                              Text(
                                                donor['student_id'] ?? 'N/A',
                                                style: GoogleFonts.inter(
                                                  fontSize: 12,
                                                  color: Colors.grey[600],
                                                ),
                                              ),
                                            ],
                                          ),
                                        ),
                                        Container(
                                          padding: const EdgeInsets.symmetric(
                                            horizontal: 12,
                                            vertical: 6,
                                          ),
                                          decoration: BoxDecoration(
                                            color: Colors.teal.withOpacity(0.1),
                                            borderRadius:
                                                BorderRadius.circular(20),
                                          ),
                                          child: Text(
                                            donor['blood_group'] ?? 'N/A',
                                            style: GoogleFonts.inter(
                                              fontSize: 14,
                                              fontWeight: FontWeight.w700,
                                              color: Colors.teal,
                                            ),
                                          ),
                                        ),
                                      ],
                                    ),
                                    const SizedBox(height: 12),
                                    Row(
                                      children: [
                                        Icon(Icons.location_on,
                                            size: 16, color: Colors.grey),
                                        const SizedBox(width: 6),
                                        Expanded(
                                          child: Text(
                                            donor['area'] ?? 'N/A',
                                            style: GoogleFonts.inter(
                                              fontSize: 13,
                                              color: Colors.grey[600],
                                            ),
                                          ),
                                        ),
                                      ],
                                    ),
                                    const SizedBox(height: 8),
                                    Row(
                                      children: [
                                        Icon(Icons.phone,
                                            size: 16, color: Colors.grey),
                                        const SizedBox(width: 6),
                                        Text(
                                          donor['phone'] ?? 'N/A',
                                          style: GoogleFonts.inter(
                                            fontSize: 13,
                                            color: Colors.grey[600],
                                          ),
                                        ),
                                      ],
                                    ),
                                    if (donor['total_donations'] != null) ...[
                                      const SizedBox(height: 8),
                                      Row(
                                        children: [
                                          Icon(Icons.favorite,
                                              size: 16, color: Colors.red),
                                          const SizedBox(width: 6),
                                          Text(
                                            '${donor['total_donations']} donations',
                                            style: GoogleFonts.inter(
                                              fontSize: 13,
                                              color: Colors.red[600],
                                            ),
                                          ),
                                        ],
                                      ),
                                    ],
                                  ],
                                ),
                              );
                            },
                          ),
          ),
        ],
      ),
    );
  }
}
