# VAS - Visitor & Attendance System

VAS is a modern web-based application designed to simplify visitor management and attendance tracking in organizations. Built with a clean and scalable architecture, VAS helps businesses monitor user activity, manage visitor records, and track attendance efficiently in one integrated system.

## Features
- Visitor management system
- Attendance tracking
- User and role management
- Authentication and access control
- Dashboard overview
- Clean and structured backend architecture

## Tech Stack
- PHP (custom / Laravel-style structure)
- Nginx (Alpine)
- Docker & Docker Compose
- MySQL 8.0
- Redis
- phpMyAdmin

## System Architecture
VAS uses a multi-container Docker setup:
- app: PHP application container
- web: Nginx web server
- db: MySQL database
- redis: caching and queue support
- phpmyadmin: database management UI

## Project Goals
VAS aims to provide a lightweight yet powerful solution for organizations that need a reliable system to manage daily attendance and visitor data without unnecessary complexity.

## Status
Currently under development

---

Built with simplicity and efficiency in mind.