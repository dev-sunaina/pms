import React from 'react';
import { useAuth } from '../../contexts/AuthContext';
import {
  FolderIcon,
  CheckIcon,
  ClockIcon,
  UsersIcon,
} from '@heroicons/react/24/outline';

const Dashboard: React.FC = () => {
  const { user } = useAuth();

  const stats = [
    {
      name: 'Active Projects',
      value: '12',
      icon: FolderIcon,
      color: 'bg-blue-500',
    },
    {
      name: 'Pending Tasks',
      value: '24',
      icon: CheckIcon,
      color: 'bg-yellow-500',
    },
    {
      name: 'Hours This Week',
      value: '32.5',
      icon: ClockIcon,
      color: 'bg-green-500',
    },
    {
      name: 'Team Members',
      value: '8',
      icon: UsersIcon,
      color: 'bg-purple-500',
    },
  ];

  return (
    <div className="py-6">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <h1 className="text-2xl font-semibold text-gray-900">
          Welcome back, {user?.name}!
        </h1>
      </div>
      <div className="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div className="py-4">
          <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            {stats.map((item) => (
              <div
                key={item.name}
                className="relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden"
              >
                <dt>
                  <div className={`absolute ${item.color} rounded-md p-3`}>
                    <item.icon className="h-6 w-6 text-white" aria-hidden="true" />
                  </div>
                  <p className="ml-16 text-sm font-medium text-gray-500 truncate">
                    {item.name}
                  </p>
                </dt>
                <dd className="ml-16 pb-6 flex items-baseline sm:pb-7">
                  <p className="text-2xl font-semibold text-gray-900">{item.value}</p>
                </dd>
              </div>
            ))}
          </div>
        </div>

        <div className="mt-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {/* Recent Projects */}
            <div className="bg-white shadow rounded-lg">
              <div className="px-4 py-5 sm:p-6">
                <h3 className="text-lg leading-6 font-medium text-gray-900">
                  Recent Projects
                </h3>
                <div className="mt-5">
                  <div className="flow-root">
                    <ul className="-my-5 divide-y divide-gray-200">
                      {[1, 2, 3].map((item) => (
                        <li key={item} className="py-4">
                          <div className="flex items-center space-x-4">
                            <div className="flex-shrink-0">
                              <div className="h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center">
                                <FolderIcon className="h-5 w-5 text-gray-500" />
                              </div>
                            </div>
                            <div className="flex-1 min-w-0">
                              <p className="text-sm font-medium text-gray-900 truncate">
                                Project {item}
                              </p>
                              <p className="text-sm text-gray-500 truncate">
                                Client Name {item}
                              </p>
                            </div>
                            <div>
                              <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                              </span>
                            </div>
                          </div>
                        </li>
                      ))}
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            {/* Recent Tasks */}
            <div className="bg-white shadow rounded-lg">
              <div className="px-4 py-5 sm:p-6">
                <h3 className="text-lg leading-6 font-medium text-gray-900">
                  Recent Tasks
                </h3>
                <div className="mt-5">
                  <div className="flow-root">
                    <ul className="-my-5 divide-y divide-gray-200">
                      {[1, 2, 3].map((item) => (
                        <li key={item} className="py-4">
                          <div className="flex items-center space-x-4">
                            <div className="flex-shrink-0">
                              <div className="h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center">
                                <CheckIcon className="h-5 w-5 text-gray-500" />
                              </div>
                            </div>
                            <div className="flex-1 min-w-0">
                              <p className="text-sm font-medium text-gray-900 truncate">
                                Task {item}
                              </p>
                              <p className="text-sm text-gray-500 truncate">
                                Due in 2 days
                              </p>
                            </div>
                            <div>
                              <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                In Progress
                              </span>
                            </div>
                          </div>
                        </li>
                      ))}
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;