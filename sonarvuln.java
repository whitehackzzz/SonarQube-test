import java.sql.*;
import java.util.Scanner;

public class VulnerableApp {
    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in);

        // Hardcoded credentials (Bad practice)
        String dbUrl = "jdbc:mysql://localhost:3306/vulnerable_db";
        String dbUser = "root";
        String dbPassword = "password"; // Hardcoded password

        try (Connection conn = DriverManager.getConnection(dbUrl, dbUser, dbPassword)) {
            System.out.println("Connected to database!");

            System.out.println("Enter username:");
            String username = scanner.nextLine();

            System.out.println("Enter password:");
            String password = scanner.nextLine();

            // **SQL Injection Vulnerability** (User input directly used in query)
            String query = "SELECT * FROM users WHERE username = '" + username + "' AND password = '" + password + "'";
            Statement stmt = conn.createStatement();
            ResultSet rs = stmt.executeQuery(query);

            if (rs.next()) {
                System.out.println("Login successful! Welcome, " + username);
            } else {
                System.out.println("Invalid credentials!");
            }

            // **Insecure File Handling**
            System.out.println("Enter file path to read:");
            String filePath = scanner.nextLine();
            java.nio.file.Files.readAllLines(java.nio.file.Paths.get(filePath))
                    .forEach(System.out::println); // No validation, leads to path traversal

        } catch (Exception e) {
            e.printStackTrace(); // **Reveals sensitive info in logs**
        }

        scanner.close();
    }
}
