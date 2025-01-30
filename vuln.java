import java.sql.*;
import java.io.*;
import java.net.*;
import javax.servlet.*;
import javax.servlet.http.*;
import org.apache.commons.io.FileUtils;

public class VulnerableApp {

    // SQL Injection vulnerability
    public void vulnerableQuery(String userInput) {
        String url = "jdbc:mysql://localhost:3306/mydatabase";
        String username = "root";
        String password = "root";
        
        try {
            Connection conn = DriverManager.getConnection(url, username, password);
            Statement stmt = conn.createStatement();
            String query = "SELECT * FROM users WHERE username = '" + userInput + "'";
            ResultSet rs = stmt.executeQuery(query);
            
            while (rs.next()) {
                System.out.println("User: " + rs.getString("username"));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    // Insecure File Handling vulnerability (Local File Inclusion - LFI)
    public void vulnerableFileRead(String fileName) {
        try {
            FileInputStream file = new FileInputStream(fileName);
            BufferedReader reader = new BufferedReader(new InputStreamReader(file));
            String line;
            while ((line = reader.readLine()) != null) {
                System.out.println(line);
            }
            reader.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    // Hardcoded sensitive data (credentials)
    public void vulnerableHardcodedCredentials() {
        String username = "admin"; // Hardcoded username
        String password = "password123"; // Hardcoded password
        
        System.out.println("Username: " + username);
        System.out.println("Password: " + password);
    }

    // Cross-Site Scripting (XSS) vulnerability
    public void vulnerableXSS(String userInput) {
        String output = "<html><body>Hello, " + userInput + "!</body></html>";
        System.out.println(output);
    }

    // Insecure Deserialization vulnerability
    public void vulnerableDeserialization(byte[] data) {
        try {
            ObjectInputStream ois = new ObjectInputStream(new ByteArrayInputStream(data));
            Object object = ois.readObject();
            System.out.println("Deserialized object: " + object);
        } catch (IOException | ClassNotFoundException e) {
            e.printStackTrace();
        }
    }

    // Cross-Origin Resource Sharing (CORS) Misconfiguration vulnerability
    public void vulnerableCORS(HttpServletRequest request, HttpServletResponse response) {
        response.setHeader("Access-Control-Allow-Origin", "*"); // Allows all origins
        response.setHeader("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE");
        response.setHeader("Access-Control-Allow-Headers", "Content-Type");
        System.out.println("CORS misconfigured, any origin can access this resource.");
    }

    // Remote Code Execution (RCE) vulnerability
    public void vulnerableRCE(String command) {
        try {
            Process process = Runtime.getRuntime().exec(command);
            BufferedReader reader = new BufferedReader(new InputStreamReader(process.getInputStream()));
            String line;
            while ((line = reader.readLine()) != null) {
                System.out.println(line);
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    // Server-Side Template Injection (SSTI) vulnerability
    public void vulnerableSSTI(String templateInput) {
        // Assuming this is used with a template engine like Thymeleaf, Velocity, or Freemarker
        String template = "Hello, ${" + templateInput + "}";
        System.out.println("Rendered Template: " + template);
    }

    // Server-Side Request Forgery (SSRF) vulnerability
    public void vulnerableSSRF(String url) {
        try {
            URL targetURL = new URL(url);
            HttpURLConnection connection = (HttpURLConnection) targetURL.openConnection();
            connection.setRequestMethod("GET");
            connection.connect();
            BufferedReader reader = new BufferedReader(new InputStreamReader(connection.getInputStream()));
            String line;
            while ((line = reader.readLine()) != null) {
                System.out.println(line);
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public static void main(String[] args) {
        VulnerableApp app = new VulnerableApp();

        // Example of SQL Injection vulnerability
        app.vulnerableQuery("admin' OR '1'='1");

        // Example of File Handling vulnerability (LFI)
        app.vulnerableFileRead("/etc/passwd");

        // Example of Hardcoded Credentials vulnerability
        app.vulnerableHardcodedCredentials();

        // Example of XSS vulnerability
        app.vulnerableXSS("<script>alert('XSS Attack');</script>");
        
        // Example of Deserialization vulnerability
        app.vulnerableDeserialization(new byte[]{});

        // Example of CORS misconfiguration vulnerability
        // Normally would be invoked in a web server context
        // app.vulnerableCORS(request, response); // Uncomment if integrated with servlet

        // Example of Remote Code Execution vulnerability
        app.vulnerableRCE("rm -rf /");

        // Example of Server-Side Template Injection (SSTI) vulnerability
        app.vulnerableSSTI("${7*7}");

        // Example of SSRF vulnerability
        app.vulnerableSSRF("http://localhost:8080");
    }
}
