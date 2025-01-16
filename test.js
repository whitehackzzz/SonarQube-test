// Example of SQL Injection
import java.sql.*;

public class SQLInjectionExample {
    public static void main(String[] args) {
        String userInput = "admin' OR 1=1 --";
        String query = "SELECT * FROM users WHERE username = '" + userInput + "';";

        try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/mydb", "user", "password");
             Statement stmt = conn.createStatement()) {
            ResultSet rs = stmt.executeQuery(query);
            while (rs.next()) {
                System.out.println("User: " + rs.getString("username"));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
}

// Example of Cross-Site Scripting (XSS)
import javax.servlet.*;
import javax.servlet.http.*;

public class XSSExample extends HttpServlet {
    protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, java.io.IOException {
        String userInput = request.getParameter("userInput");
        response.getWriter().write("<html><body>");
        response.getWriter().write("<h1>User Input: " + userInput + "</h1>"); // Vulnerable to XSS
        response.getWriter().write("</body></html>");
    }
}

// Example of Insecure File Handling
import java.io.*;

public class InsecureFileHandlingExample {
    public static void main(String[] args) {
        String filename = "C:\\Users\\admin\\Documents\\sensitive_file.txt";
        File file = new File(filename);
        try (BufferedReader reader = new BufferedReader(new FileReader(file))) {
            String line;
            while ((line = reader.readLine()) != null) {
                System.out.println(line);
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}

// Example of Hardcoded Credentials
public class HardcodedCredentialsExample {
    public static void main(String[] args) {
        String username = "admin"; // Hardcoded username
        String password = "password123"; // Hardcoded password

        if ("admin".equals(username) && "password123".equals(password)) {
            System.out.println("Access granted!");
        } else {
            System.out.println("Access denied!");
        }
    }
}
