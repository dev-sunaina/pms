const axios = require('axios');

const API_BASE = 'http://localhost:12000/api';

async function testApp() {
    console.log('🚀 Testing Project Management Tool...\n');
    
    try {
        // Test 1: Register a user
        console.log('1. Testing user registration...');
        const registerResponse = await axios.post(`${API_BASE}/register`, {
            name: 'John Doe',
            email: `john${Date.now()}@example.com`,
            password: 'password123',
            password_confirmation: 'password123'
        }, {
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
        });
        
        const token = registerResponse.data.token;
        console.log('✅ User registered successfully');
        console.log(`   User: ${registerResponse.data.user.name} (${registerResponse.data.user.email})`);
        
        // Test 2: Get user info
        console.log('\n2. Testing authenticated user endpoint...');
        const userResponse = await axios.get(`${API_BASE}/user`, {
            headers: { 
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json' 
            }
        });
        console.log('✅ User info retrieved successfully');
        console.log(`   User ID: ${userResponse.data.id}, Role: ${userResponse.data.role}`);
        
        // Test 3: Create a team
        console.log('\n3. Testing team creation...');
        const teamResponse = await axios.post(`${API_BASE}/teams`, {
            name: 'Development Team',
            description: 'Main development team for the project'
        }, {
            headers: { 
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json' 
            }
        });
        console.log('✅ Team created successfully');
        console.log(`   Team: ${teamResponse.data.name} (ID: ${teamResponse.data.id})`);
        
        const teamId = teamResponse.data.id;
        
        // Test 4: Create a project
        console.log('\n4. Testing project creation...');
        const projectResponse = await axios.post(`${API_BASE}/projects`, {
            name: 'Website Redesign',
            description: 'Complete redesign of company website',
            team_id: teamId,
            start_date: '2025-07-08',
            end_date: '2025-08-08',
            status: 'active'
        }, {
            headers: { 
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json' 
            }
        });
        console.log('✅ Project created successfully');
        console.log(`   Project: ${projectResponse.data.name} (ID: ${projectResponse.data.id})`);
        
        const projectId = projectResponse.data.id;
        
        // Test 5: Create a task
        console.log('\n5. Testing task creation...');
        const taskResponse = await axios.post(`${API_BASE}/tasks`, {
            title: 'Design homepage mockup',
            description: 'Create wireframes and mockups for the new homepage',
            project_id: projectId,
            assigned_to: userResponse.data.id,
            priority: 'high',
            status: 'todo',
            due_date: '2025-07-15'
        }, {
            headers: { 
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json' 
            }
        });
        console.log('✅ Task created successfully');
        console.log(`   Task: ${taskResponse.data.title} (ID: ${taskResponse.data.id})`);
        
        // Test 6: Create a timesheet entry
        console.log('\n6. Testing timesheet creation...');
        const timesheetResponse = await axios.post(`${API_BASE}/timesheets`, {
            project_id: projectId,
            task_id: taskResponse.data.id,
            hours: 4.5,
            description: 'Worked on homepage mockup design',
            date: '2025-07-08'
        }, {
            headers: { 
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json' 
            }
        });
        console.log('✅ Timesheet entry created successfully');
        console.log(`   Hours: ${timesheetResponse.data.hours}, Date: ${timesheetResponse.data.date}`);
        
        // Test 7: Send a message
        console.log('\n7. Testing message creation...');
        const messageResponse = await axios.post(`${API_BASE}/messages`, {
            team_id: teamId,
            message: 'Hello team! The project has been set up successfully.'
        }, {
            headers: { 
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json' 
            }
        });
        console.log('✅ Message sent successfully');
        console.log(`   Message ID: ${messageResponse.data.id}`);
        
        // Test 8: Get team messages
        console.log('\n8. Testing team messages retrieval...');
        const messagesResponse = await axios.get(`${API_BASE}/teams/${teamId}/messages`, {
            headers: { 
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json' 
            }
        });
        console.log('✅ Team messages retrieved successfully');
        console.log(`   Messages count: ${messagesResponse.data.length}`);
        
        console.log('\n🎉 All tests passed! The Project Management Tool is working correctly.');
        console.log('\n📊 Summary:');
        console.log(`   - User: ${registerResponse.data.user.name}`);
        console.log(`   - Team: ${teamResponse.data.name}`);
        console.log(`   - Project: ${projectResponse.data.name}`);
        console.log(`   - Task: ${taskResponse.data.title}`);
        console.log(`   - Timesheet: ${timesheetResponse.data.hours} hours logged`);
        console.log(`   - Messages: ${messagesResponse.data.length} team messages`);
        
    } catch (error) {
        console.error('❌ Test failed:', error.response?.data || error.message);
        if (error.response?.data?.errors) {
            console.error('   Validation errors:', error.response.data.errors);
        }
    }
}

testApp();