using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using WorkTimeLoggerEmulator.WinForms.Properties;

namespace WorkTimeLoggerEmulator.WinForms
{
    public partial class Form1 : Form
    {
        private readonly Settings _settings = new Settings();

        public Form1()
        {
            InitializeComponent();
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            label1.Text = _settings.endpoint;
            label2.Text = _settings.token;
        }
    }
}
