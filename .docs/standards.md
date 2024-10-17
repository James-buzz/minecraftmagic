# Backend Standards
- repositories will return null if not found.
- service classes can throw exceptions if an unexpected scenario occurs with a custom exception. 
- presentation layer (controller) will handle exceptions and return a proper response to the client.

- repositories are easy to mock rather than models in unit tests. 
