@linemob
Feature: Example testing
  Background:
    Given ทดสอบบอท "mock_bot"
  Scenario:
    When พิมพ์ข้อความว่า "test"
    Then ข้อความจากไลน์บอท จะต้องตอบกลับประกอบด้วยคำว่า Fallback message
