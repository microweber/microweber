import java.io.IOException;
import java.util.ArrayList;
import java.util.Properties;

/**
 * This java class implements a basic HTTP server aimed at testing the
 * jQuery.validate AJAX capabilities. Note that this file shouldn't be taken as
 * best practice for java back end development. There are much better frameworks
 * to do server side processing in Java, for instance, the Play Framework.
 * 
 * @author Olivier Refalo
 */
public class AjaxTestServer extends NanoHTTPD {

	private static final int PORT = 9173;
	public static final String MIME_JSON = "application/json";

	public AjaxTestServer() throws IOException {
		super(PORT);
	}

	public class AjaxValidationFieldResponse {

		// the html field id
		private String id;
		// true - field is valid
		private Boolean status;

		public AjaxValidationFieldResponse(String fieldId, Boolean s) {
			id = fieldId;
			status = s;
		}

		public String toString() {

			StringBuffer json = new StringBuffer();
			json.append("[\"").append(id).append("\",").append(status.toString()).append(']');
			return json.toString();
		}
	}

	public class AjaxValidationFormResponse {

		// the html field id
		private String id;

		// true, the field is valid : the client logic displays a green prompt
		// false, the field is invalid : the client logic displays a red prompt
		private Boolean status;

		// either the string to display in the prompt or an error
		// selector to pick the error message from the translation.js
		private String error;

		public AjaxValidationFormResponse(String fieldId, Boolean s, String err) {
			id = fieldId;
			status = s;
			error = err;
		}

		public String toString() {

			StringBuffer json = new StringBuffer();
			json.append("[\"").append(id).append("\",").append(status).append(",\"").append(error.toString()).append("\"]");
			return json.toString();
		}
	}

	public Response serve(String uri, String method, Properties header, Properties parms) {

		// field validation
		if ("/ajaxValidateFieldUser".equals(uri)) {
			System.out.println("-> " + method + " '" + uri + "'");

			// purposely sleep to let the UI display the AJAX loading prompts
			sleep(3000);

			String fieldId = parms.getProperty("fieldId");
			String fieldValue = parms.getProperty("fieldValue");

			AjaxValidationFieldResponse result = new AjaxValidationFieldResponse(fieldId, new Boolean(
					"karnius".equals(fieldValue)));
			// return ["fieldid", true or false]
			return new NanoHTTPD.Response(HTTP_OK, MIME_JSON, result.toString());
		}
		// field validation
		else if ("/ajaxValidateFieldName".equals(uri)) {

			System.out.println("-> " + method + " '" + uri + "'");

			// purposely sleep to let the UI display the AJAX loading prompts
			sleep(3000);

			String fieldId = parms.getProperty("fieldId");
			String fieldValue = parms.getProperty("fieldValue");

			AjaxValidationFieldResponse result = new AjaxValidationFieldResponse(fieldId, new Boolean(
					"duncan".equals(fieldValue)));
			// return ["fieldid", true or false]
			return new NanoHTTPD.Response(HTTP_OK, MIME_JSON, result.toString());
		}
		// form validation, we get the form data (read: all the form fields), we
		// return ALL the errors
		else if ("/ajaxSubmitForm".equals(uri)) {

			System.out.println("-> " + method + " '" + uri + "'");

			// purposely sleep to let the UI display the AJAX loading prompts
			sleep(1000);

			ArrayList<AjaxValidationFormResponse> errors = new ArrayList<AjaxValidationFormResponse>();

			String user = parms.getProperty("user");
			String firstname = parms.getProperty("firstname");
			String email = parms.getProperty("email");

			if (!"karnius".equals(user)) {
				// error selector: indirection to the error message -> done in
				// javascript
				errors.add(new AjaxValidationFormResponse("user", false, "ajaxUserCall"));
			}

			if (!"duncan".equals(firstname)) {
				errors.add(new AjaxValidationFormResponse("firstname", false, "Please enter DUNCAN"));
			} else {
				errors.add(new AjaxValidationFormResponse("firstname", true, "You got it!"));
			}

			if (!"someone@here.com".equals(email)) {

				errors.add(new AjaxValidationFormResponse("email", false, "The email doesn't match someone@here.com"));
			}
			
			String json = "true";
			if (errors.size() != 0) {
				json = genJSON(errors);
			}

			return new NanoHTTPD.Response(HTTP_OK, MIME_JSON, json);
		}
		return super.serve(uri, method, header, parms);
	}

	/**
	 * Form validation error generation Generates a list of errors
	 * 
	 * @param errors
	 * @return [["field1", "this field is required<br/>
	 *         it doesn't match<br/>
	 *         "],["field2","another error"]]
	 */
	private String genJSON(ArrayList<AjaxValidationFormResponse> errors) {
		StringBuffer json = new StringBuffer();
		json.append('[');
		for (int i = 0; i < errors.size(); i++) {

			AjaxValidationFormResponse err = errors.get(i);
			json.append(err.toString());
			if (i < errors.size() - 1) {
				json.append(',');
			}
		}
		json.append(']');
		return json.toString();
	}

	/**
	 * Sleeps the current thread for the given delay
	 * 
	 * @param duration
	 *            in milliseconds
	 * */
	private void sleep(long duration) {
		try {
			Thread.sleep(duration);
		} catch (InterruptedException e) {

			e.printStackTrace();
		}
	}

	/**
	 * Application start point, starts the httpd server
	 * 
	 * @param args
	 *            command line arguments
	 */
	public static void main(String[] args) {
		try {
			new AjaxTestServer();
		} catch (IOException ioe) {
			System.err.println("Couldn't start server:\n" + ioe);
			System.exit(-1);
		}
		System.out.println("Listening on port " + PORT + ". Hit Enter to stop.\nPlease open your browsers to http://localhost:"
				+ PORT);
		try {
			System.in.read();
		} catch (Throwable t) {
		}
	}
}