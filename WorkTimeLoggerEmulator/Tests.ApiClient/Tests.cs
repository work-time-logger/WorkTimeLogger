using System;
using NUnit.Framework;
using WorkTimeLoggerEmulator.ApiClient;

namespace Tests.ApiClient
{
    [TestFixture]
    public class Tests
    {
        [Test]
        public void Test1()
        {
            var client = new Client("http://10.20.30.40/hw/", "VERY_SECRET_TEST_TOKEN");

            var test = client.Query("686790c6");

            Assert.AreEqual("e22d1887-ccbc-471b-b725-6f79b1807e2e", test.employee);
            Assert.AreEqual("Janusz", test.first_name);
            Assert.AreEqual("Kowalski", test.last_name);

            var start = client.Start("686790c6");
            var stop = client.Stop("686790c6", start.entry);
        }
    }
}