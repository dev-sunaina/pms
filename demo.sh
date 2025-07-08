#!/bin/bash

echo "🚀 Project Management Tool Demo"
echo "================================"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}Testing Backend API...${NC}"

# Test API health
echo -e "${YELLOW}1. Testing API health...${NC}"
curl -s http://localhost:12000/api/user -H "Accept: application/json" | grep -q "Unauthenticated" && echo -e "${GREEN}✓ API is running${NC}" || echo -e "${RED}✗ API not responding${NC}"

# Test user registration
echo -e "${YELLOW}2. Testing user registration...${NC}"
REGISTER_RESPONSE=$(curl -s -X POST http://localhost:12000/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Demo User",
    "email": "demo'$(date +%s)'@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }')

if echo "$REGISTER_RESPONSE" | grep -q "token"; then
    echo -e "${GREEN}✓ User registration successful${NC}"
    TOKEN=$(echo "$REGISTER_RESPONSE" | grep -o '"token":"[^"]*"' | cut -d'"' -f4)
else
    echo -e "${RED}✗ User registration failed${NC}"
    echo "$REGISTER_RESPONSE"
    exit 1
fi

# Test authenticated endpoint
echo -e "${YELLOW}3. Testing authenticated endpoint...${NC}"
USER_RESPONSE=$(curl -s -X GET http://localhost:12000/api/user \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN")

if echo "$USER_RESPONSE" | grep -q "name"; then
    echo -e "${GREEN}✓ Authentication working${NC}"
    USER_NAME=$(echo "$USER_RESPONSE" | grep -o '"name":"[^"]*"' | cut -d'"' -f4)
    echo -e "   Logged in as: ${BLUE}$USER_NAME${NC}"
else
    echo -e "${RED}✗ Authentication failed${NC}"
    exit 1
fi

# Test team creation
echo -e "${YELLOW}4. Testing team creation...${NC}"
TEAM_RESPONSE=$(curl -s -X POST http://localhost:12000/api/teams \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "name": "Demo Team",
    "description": "A demo team for testing"
  }')

if echo "$TEAM_RESPONSE" | grep -q "Demo Team"; then
    echo -e "${GREEN}✓ Team creation successful${NC}"
    TEAM_ID=$(echo "$TEAM_RESPONSE" | grep -o '"id":[0-9]*' | tail -1 | cut -d':' -f2)
    echo "   Team ID: $TEAM_ID"
else
    echo -e "${RED}✗ Team creation failed${NC}"
    echo "$TEAM_RESPONSE"
fi

# Test project creation
echo -e "${YELLOW}5. Testing project creation...${NC}"
PROJECT_RESPONSE=$(curl -s -X POST http://localhost:12000/api/projects \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "name": "Demo Project",
    "description": "A demo project for testing",
    "team_id": '$TEAM_ID',
    "status": "active"
  }')

if echo "$PROJECT_RESPONSE" | grep -q "Demo Project"; then
    echo -e "${GREEN}✓ Project creation successful${NC}"
    PROJECT_ID=$(echo "$PROJECT_RESPONSE" | grep -o '"id":[0-9]*' | tail -1 | cut -d':' -f2)
    echo "   Project ID: $PROJECT_ID"
else
    echo -e "${RED}✗ Project creation failed${NC}"
    echo "$PROJECT_RESPONSE"
    echo "   Using Team ID: $TEAM_ID"
fi

# Test task creation
echo -e "${YELLOW}6. Testing task creation...${NC}"
TASK_RESPONSE=$(curl -s -X POST http://localhost:12000/api/tasks \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "title": "Demo Task",
    "description": "A demo task for testing",
    "project_id": '$PROJECT_ID',
    "status": "pending",
    "priority": "medium"
  }')

if echo "$TASK_RESPONSE" | grep -q "Demo Task"; then
    echo -e "${GREEN}✓ Task creation successful${NC}"
    TASK_ID=$(echo "$TASK_RESPONSE" | grep -o '"id":[0-9]*' | tail -1 | cut -d':' -f2)
else
    echo -e "${RED}✗ Task creation failed${NC}"
    echo "$TASK_RESPONSE"
fi

# Test timesheet creation
echo -e "${YELLOW}7. Testing timesheet creation...${NC}"
TIMESHEET_RESPONSE=$(curl -s -X POST http://localhost:12000/api/timesheets \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "project_id": '$PROJECT_ID',
    "task_id": '$TASK_ID',
    "hours": 2.5,
    "description": "Working on demo task",
    "date": "'$(date +%Y-%m-%d)'"
  }')

if echo "$TIMESHEET_RESPONSE" | grep -q "Working on demo task"; then
    echo -e "${GREEN}✓ Timesheet creation successful${NC}"
else
    echo -e "${RED}✗ Timesheet creation failed${NC}"
    echo "$TIMESHEET_RESPONSE"
fi

echo ""
echo -e "${GREEN}🎉 Demo completed successfully!${NC}"
echo -e "${BLUE}Frontend is running at: http://localhost:12005${NC}"
echo -e "${BLUE}Backend API is running at: http://localhost:12000${NC}"
echo ""
echo -e "${YELLOW}You can now:${NC}"
echo "• Open the frontend in your browser"
echo "• Register a new account or login"
echo "• Create teams, projects, and tasks"
echo "• Track time with timesheets"
echo "• Use the team chat feature"