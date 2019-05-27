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
    public partial class MainEmulatorForm : Form
    {
        private readonly Settings _settings = new Settings();

        public MainEmulatorForm()
        {
            InitializeComponent();
        }

        private void Form_Load(object sender, EventArgs e)
        {
            label1.Text = _settings.endpoint;
            label2.Text = _settings.token;
        }
    }
}
