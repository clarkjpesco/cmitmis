// csrf-helpers.js - Enhanced CSRF token management with file upload support

class CSRFManager {
  constructor() {
    this.tokenFieldName = "token";
    this.metaTagName = "csrf-token";
    this.endpoint = "/csrf-token";
  }

  // Get current CSRF token from the page
  getCurrentToken() {
    const csrfTokenInput = document.querySelector(
      `input[name="${this.tokenFieldName}"]`
    );
    return csrfTokenInput ? csrfTokenInput.value : "";
  }

  // Update CSRF token in all locations on the page
  updateToken(newToken) {
    // Update hidden form inputs
    const tokenInputs = document.querySelectorAll(
      `input[name="${this.tokenFieldName}"]`
    );
    tokenInputs.forEach((input) => {
      input.value = newToken;
    });

    // Update meta tag if it exists
    const metaToken = document.querySelector(
      `meta[name="${this.metaTagName}"]`
    );
    if (metaToken) {
      metaToken.setAttribute("content", newToken);
    }

    // Update any data attributes if needed
    const tokenDataElements = document.querySelectorAll("[data-csrf-token]");
    tokenDataElements.forEach((element) => {
      element.dataset.csrfToken = newToken;
    });

    console.log("CSRF token updated:", newToken.substring(0, 8) + "...");
    return newToken;
  }

  // Refresh CSRF token from server
  async refreshToken() {
    try {
      const response = await fetch(this.endpoint, {
        method: "GET",
        credentials: "same-origin",
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
      });

      if (response.ok) {
        const data = await response.json();
        if (data.token) {
          return this.updateToken(data.token);
        }
      } else if (response.status === 419) {
        console.warn("CSRF token expired, reloading page...");
        window.location.reload();
      }
    } catch (error) {
      console.error("Error refreshing CSRF token:", error);
    }

    return this.getCurrentToken();
  }

  // Enhanced fetch with CSRF protection and file upload support
  async csrfFetch(url, options = {}) {
    // Set up default options
    const fetchOptions = {
      credentials: "same-origin",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        ...options.headers,
      },
      ...options,
    };

    // For state-changing methods, add CSRF token
    const method = fetchOptions.method
      ? fetchOptions.method.toUpperCase()
      : "GET";
    const stateChangingMethods = ["POST", "PUT", "PATCH", "DELETE"];

    if (stateChangingMethods.includes(method)) {
      // Always add CSRF token to headers
      fetchOptions.headers["X-CSRF-TOKEN"] = this.getCurrentToken();

      // Handle different body types
      if (fetchOptions.body) {
        if (fetchOptions.body instanceof FormData) {
          // For FormData (file uploads), add token to FormData and don't set Content-Type
          fetchOptions.body.append(this.tokenFieldName, this.getCurrentToken());
          // Remove Content-Type header to let browser set it with boundary
          delete fetchOptions.headers["Content-Type"];
        } else if (typeof fetchOptions.body === "string") {
          // Body is already a string (JSON, etc.)
          // Set Content-Type if not already set
          if (!fetchOptions.headers["Content-Type"]) {
            fetchOptions.headers["Content-Type"] = "application/json";
          }
        } else if (
          typeof fetchOptions.body === "object" &&
          fetchOptions.body !== null
        ) {
          // Body is an object - convert to JSON and add token
          const bodyData = { ...fetchOptions.body };
          bodyData[this.tokenFieldName] = this.getCurrentToken();
          fetchOptions.body = JSON.stringify(bodyData);
          fetchOptions.headers["Content-Type"] = "application/json";
        }
      }
    } else {
      // For GET requests, set default Content-Type if not specified
      if (!fetchOptions.headers["Content-Type"]) {
        fetchOptions.headers["Content-Type"] = "application/json";
      }
    }

    try {
      const response = await fetch(url, fetchOptions);

      // If we get a CSRF error, refresh token and retry
      if (response.status === 419) {
        console.warn("CSRF token mismatch, refreshing token and retrying...");
        await this.refreshToken();

        // Update token in headers for retry
        if (stateChangingMethods.includes(method)) {
          fetchOptions.headers["X-CSRF-TOKEN"] = this.getCurrentToken();

          // Update token in body if applicable
          if (fetchOptions.body instanceof FormData) {
            // Remove old token and add new one
            fetchOptions.body.delete(this.tokenFieldName);
            fetchOptions.body.append(
              this.tokenFieldName,
              this.getCurrentToken()
            );
          } else if (typeof fetchOptions.body === "string") {
            try {
              const bodyData = JSON.parse(fetchOptions.body);
              bodyData[this.tokenFieldName] = this.getCurrentToken();
              fetchOptions.body = JSON.stringify(bodyData);
            } catch (e) {
              // Body is not JSON, can't update token in body
              console.warn("Could not update CSRF token in non-JSON body");
            }
          }
        }

        return fetch(url, fetchOptions);
      }

      return response;
    } catch (error) {
      console.error("Request failed:", error);
      throw error;
    }
  }

  // Helper method to create FormData with CSRF token
  createFormData(data = {}) {
    const formData = new FormData();

    // Add CSRF token first
    formData.append(this.tokenFieldName, this.getCurrentToken());

    // Add other data
    for (const [key, value] of Object.entries(data)) {
      if (value instanceof File) {
        formData.append(key, value);
      } else if (Array.isArray(value)) {
        value.forEach((item, index) => {
          formData.append(`${key}[${index}]`, item);
        });
      } else {
        formData.append(key, value);
      }
    }

    return formData;
  }

  // Helper method for simple form submissions
  async submitForm(url, formData, options = {}) {
    const fetchOptions = {
      method: "POST",
      body:
        formData instanceof FormData ? formData : this.createFormData(formData),
      ...options,
    };

    return this.csrfFetch(url, fetchOptions);
  }
}

// Export as default
window.CSRFManager = CSRFManager;
window.csrfManager = new CSRFManager();
